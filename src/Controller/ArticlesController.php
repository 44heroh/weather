<?php

namespace App\Controller;

use App\Objects\Coord;
use App\Service\OpenWeatherApiService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class ArticlesController extends AbstractController
{
    /**
     * @var OpenWeatherApiService
     */
    private OpenWeatherApiService $openWeatherApiService;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * ArticlesController constructor.
     * @param OpenWeatherApiService $openWeatherApiService
     */
    public function __construct(
        OpenWeatherApiService $openWeatherApiService,
        LoggerInterface $logger
    )
    {
        $this->openWeatherApiService = $openWeatherApiService;
        $this->logger = $logger;
    }

    #[Route(path: '/valid', name: 'valid', methods: ['GET'])]
    public function valid(): Response
    {
        $validator = Validation::createValidator();

        $input = [
            'name' => [
                'first_name' => 'Fabien',
                'last_name' => 'Potencier',
            ],
            'email' => 'test@email.tld',
            'simple' => 'hello',
            'eye_color' => 3,
            'file' => null,
            'password' => 'testtesttest',
            'tags' => [
                [
                    'slug' => 'symfony_doc',
                    'label' => 'symfony doc',
                ],
            ],
        ];

        $groups = new Assert\GroupSequence(['Default', 'custom']);

        $constraint = new Assert\Collection([
            // ключи соответствуют ключам в массиве ввода
            'name' => new Assert\Collection([
                'first_name' => new Assert\Length(['max' => 101]),
                'last_name' => new Assert\Length(['min' => 1]),
            ]),
            'email' => new Assert\Email(),
            'simple' => new Assert\Length(['min' => 1]),
            'eye_color' => new Assert\Choice([3, 4]),
            'file' => new Assert\File(),
            'password' => new Assert\Length(['min' => 8]),
            'tags' => new Assert\Optional([
                new Assert\Type('array'),
                new Assert\Count(['min' => 1]),
                new Assert\All([
                    new Assert\Collection([
                        'slug' => [
                            new Assert\NotBlank(),
                            new Assert\Type(['type' => 'string']),
                        ],
                        'label' => [
                            new Assert\NotBlank(),
                        ],
                    ]),
//                    new CustomUniqueTagValidator(['groups' => 'custom']),
                ]),
            ]),
        ]);

        $violations = $validator->validate($input, $constraint, $groups);

        $result = [];
        if (count($violations) === 0) {
            // Все поля прошли валидацию
            echo "Данные прошли валидацию.";
        } else {
            // Обнаружены ошибки валидации
            foreach ($violations as $violation) {
                dump($violation);
                echo $violation->getMessage()."\n";
            }
        }

        return $this->json(["success" => true]);
    }

    #[Route(path: '/valid-my', name: 'myValid', methods: ['GET'])]
    public function myValid(): Response
    {
        $data = [
            "cod" => "200",
            "message" => 0,
            "cnt" => 40,
            "list" => [
                [
                    "dt" => 1686139200,
                    "main" => [
                        "temp" => 20.23,
                        "feels_like" => 19.48,
                        "temp_min" => 18.52,
                        "temp_max" => 20.23,
                        "pressure" => 1019,
                        "sea_level" => 1019,
                        "grnd_level" => 995,
                        "humidity" => 45,
                        "temp_kf" => 1.71,
                    ],
                    "weather" => [
                        [
                            "id" => 802,
                            "main" => "Clouds",
                            "description" => "scattered clouds",
                            "icon" => "03d",
                        ],
                    ],
                    "clouds" => [
                        "all" => 29,
                    ],
                    "wind" => [
                        "speed" => 4.27,
                        "deg" => 250,
                        "gust" => 8.36,
                    ],
                    "visibility" => 10000,
                    "pop" => 0,
                    "sys" => [
                        "pod" => "d",
                    ],
                    "dt_txt" => "2023-06-07 12:00:00",
                ],
                [
                    "dt" => 1686150000,
                    "main" => [
                        "temp" => 20.07,
                        "feels_like" => 19.46,
                        "temp_min" => 19.57,
                        "temp_max" => 20.07,
                        "pressure" => 1018,
                        "sea_level" => 1018,
                        "grnd_level" => 994,
                        "humidity" => 51,
                        "temp_kf" => 0.5,
                    ],
                    "weather" => [
                        [
                            "id" => 802,
                            "main" => "Clouds",
                            "description" => "scattered clouds",
                            "icon" => "03d",
                        ],
                    ],
                    "clouds" => [
                        "all" => 47,
                    ],
                    "wind" => [
                        "speed" => 4.08,
                        "deg" => 244,
                        "gust" => 8.52,
                    ],
                    "visibility" => 10000,
                    "pop" => 0,
                    "sys" => [
                        "pod" => "d",
                    ],
                    "dt_txt" => "2023-06-07 15:00:00",
                ],
            ],
            "city" => [
                "id" => 475259,
                "name" => "Verkhneye Valuyevo",
                "coord" => [
                    "lat" => 55.582,
                    "lon" => 37.3855
                ],
                "country" => "RU",
                "population" => 0,
                "timezone" => 10800,
                "sunrise" => 1686099036,
                "sunset" => 1686161288,
            ]
        ];

        $validator = Validation::createValidator();
        $groups = new Assert\GroupSequence(['Default', 'custom']);

        $constraints = new Assert\Collection([
            'fields' => [
                'cod' => new Assert\EqualTo('200'),
                'list' => new Assert\NotNull(),
            ],
            'allowExtraFields' => true,
            'allowMissingFields' => false
        ]);

        $violations = $validator->validate($data, $constraints, $groups);

        $result = [];
        if (count($violations) === 0) {
            // Все поля прошли валидацию
            echo "Данные прошли валидацию.";
        } else {
            // Обнаружены ошибки валидации
            foreach ($violations as $violation) {
                dump($violation);
                echo $violation->getMessage()."\n";
            }
        }


        return $this->json($result);
    }

    #[Route(path: '/articles', name: 'articles', methods: ['GET'])]
    public function list(): Response
    {
        $coord = new Coord("dsfdsf", "dsfdsf");

        try {
            $result = $this->openWeatherApiService->fetchForecastInfo($coord);
        } catch(HttpException $e) {
            $this->logger->error($e->getMessage());
        }

        return $this->json($result);
    }
}
