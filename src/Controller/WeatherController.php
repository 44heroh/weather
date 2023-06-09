<?php


namespace App\Controller;


use App\Service\ImportWeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class WeatherController extends AbstractController
{
    /**
     * @var ImportWeatherService
     */
    private ImportWeatherService $importWeatherService;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * WeatherController constructor.
     * @param ImportWeatherService $importWeatherService
     */
    public function __construct(
        ImportWeatherService $importWeatherService,
        LoggerInterface $logger
    )
    {
        $this->importWeatherService = $importWeatherService;
        $this->logger = $logger;
    }

    // можно было отправить на exec команду отправить
    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    #[Route(path: '/weather/import', name: 'weathers-import', methods: ['GET'])]
    public function import(): Response
    {
        $strExec = 'nohup php ../bin/console app:import-weather > /dev/null 2>&1 &';

        $output=null;
        $retval=null;
        exec($strExec, $output, $retval);

        return $this->json(['success' => true]);
    }
}