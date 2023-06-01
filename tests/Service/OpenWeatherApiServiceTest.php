<?php

namespace App\Tests\Service;

use App\Service\OpenWeatherApiService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OpenWeatherApiServiceTest extends KernelTestCase
{

    public function testRequestTrue()
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        // Получение сервиса из контейнера
        $openWeatherApiService = $container->get(OpenWeatherApiService::class);

        $expected = 40;
        $response = $openWeatherApiService->request(
            [
                "lat" => 55.582026,
                "lon" => 37.3855235
            ]
        );

        $this->assertCount($expected, $response->list, "doesn't contains 40 elements");
    }

    public function testRequestWithoutParams()
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        // Получение сервиса из контейнера
        $openWeatherApiService = $container->get(OpenWeatherApiService::class);

        try {
            $response = $openWeatherApiService->request(
                [

                ]
            );

        } catch (\Exception $e) {
            $this->assertEquals("not found lat or lon", $e->getMessage());
        }

    }
}
