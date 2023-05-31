<?php

namespace App\Controller;

use App\Service\OpenWeatherApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    /**
     * @var OpenWeatherApiService
     */
    private $openWeatherApiService;

    /**
     * ArticlesController constructor.
     * @param OpenWeatherApiService $openWeatherApiService
     */
    public function __construct(OpenWeatherApiService $openWeatherApiService)
    {
        $this->openWeatherApiService = $openWeatherApiService;
    }

    #[Route(path: '/articles', name: 'articles', methods: ['GET'])]
    public function list(): Response
    {
        $params = [
            "lat" => 55.58202,
            "lon" => 37.3855235
        ];

        $result = $this->openWeatherApiService->request($params);

        dump($result);

        return new Response('Welcome to Latte and Code ');
    }
}
