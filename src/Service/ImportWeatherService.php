<?php


namespace App\Service;


use App\Entity\Weather;
use App\Entity\City;
use App\Objects\Coord;
use App\Repository\CityRepository;
use App\Repository\WeatherRepository;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;

class ImportWeatherService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var OpenWeatherApiService
     */
    private OpenWeatherApiService $openWeatherApiService;

    /**
     * @var WeatherRepository
     */
    private WeatherRepository $weatherRepository;

    /**
     * @var CityRepository
     */
    private CityRepository $cityRepository;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

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


    public function import() : bool
    {
        $cities = $this->cityRepository->findAll();

        foreach ($cities as $city) {
            try {
                $response = $this->openWeatherApiService->fetchForecastInfo(
                    new Coord($city->getLat(), $city->getLon())
                );
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                throw $e;
            }

            foreach ($response as $weather) {
                $weather->setCity($city);
                $this->entityManager->persist($weather);
            }
        }

        $this->entityManager->flush();

        return true;
    }

}