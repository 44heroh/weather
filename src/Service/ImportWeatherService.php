<?php


namespace App\Service;


use App\Entity\Weather;
use App\Repository\CityRepository;
use App\Repository\WeatherRepository;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;

class ImportWeatherService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var OpenWeatherApiService
     */
    private $openWeatherApiService;

    /**
     * @var WeatherRepository
     */
    private $weatherRepository;

    /**
     * @var CityRepository
     */
    private $cityRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ImportWeatherService constructor.
     * @param EntityManagerInterface $entityManager
     * @param OpenWeatherApiService $openWeatherApiService
     * @param WeatherRepository $weatherRepository
     * @param CityRepository $cityRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        OpenWeatherApiService $openWeatherApiService,
        WeatherRepository $weatherRepository,
        CityRepository $cityRepository,
        LoggerInterface $logger
    )
    {
        $this->entityManager = $entityManager;
        $this->openWeatherApiService = $openWeatherApiService;
        $this->weatherRepository = $weatherRepository;
        $this->cityRepository = $cityRepository;
        $this->logger = $logger;
    }


    public function import() {
        $cities = $this->cityRepository->findAll();

        foreach ($cities as $city) {
            try {
                $response[$city->getId()] = $this->openWeatherApiService->request(
                    [
                        "lat" => $city->getLat(),
                        "lon" => $city->getLon()
                    ]
                );
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
            }
        }


        foreach ($response as $key => $value) {
            foreach ($value->list as $keyVal => $item) {
                $weather = new Weather();
                $weather->setTemperature($item->main->temp);
                $weather->setClouds($item->clouds->all);
                $weather->setDate(new \DateTime($item->dt_txt));
                $this->entityManager->persist($weather);
                $this->entityManager->flush();
            }
        }

        return $response;
    }
}