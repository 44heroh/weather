<?php


namespace App\Service;

use App\Config\WeatherParams;
use App\Entity\Weather;
use App\Objects\Coord;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Класс взаимодействия с api OpenWeather
 * Class OpenWeatherApiService
 * @package App\Service
 */
class OpenWeatherApiService
{
    /**
     * @var WeatherParams
     */
    private WeatherParams $params;
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;
    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $client;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * OpenWeatherApiService constructor.
     * @param WeatherParams $params
     * @param SerializerInterface $serializer
     * @param HttpClientInterface $client
     * @param LoggerInterface $logger
     */
    public function __construct(
        WeatherParams $params,
        SerializerInterface $serializer,
        HttpClientInterface $client,
        LoggerInterface $logger
    )
    {
        $this->params = $params;
        $this->serializer = $serializer;
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * @param Coord $coord
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function fetchForecastInfo(Coord $coord): mixed {
        if (!$coord->getLat() || !$coord->getLon())
            throw new \Exception("not found lat or lon");

        if($this->params->getUrl() == null)
            throw new \Exception("not found url");

        if($this->params->getAppid() == null)
            throw new \Exception("not found appid");

        $url = $this->params->getUrl();
        $appId = $this->params->getAppid();

        $url = $url . "?" . "&lat=" . $coord->getLat() . "&lon=" . $coord->getLon() . "&appid=" . $appId . "&units=metric";

        $options = [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'verify_host' => false,   // Verify the certificate's hostname against the requested hostname
            'verify_peer' => false,   // Verify the certificate's authenticity against a trusted CA
        ];

        $errors = [];
        try {
            $response = $this->client->request('GET', $url, $options);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }

//        $content = $response->getContent();
        $content = $response->toArray();

        $validator = Validation::createValidator();
        $constraints = new Assert\Collection([
            'fields' => [
                'cod' => new Assert\EqualTo('200'),
                'list' => new Assert\NotNull(),
            ],
            'allowExtraFields' => true,
            'allowMissingFields' => false
        ]);

        $violations = $validator->validate($content, $constraints);

        $result = [];
        if (count($violations) === 0) {
            // Все поля прошли валидацию
            $weathers = [];
            foreach($content["list"] as $key => $value) {
                $weathers[] = $this->serializer->denormalize($value, Weather::class);
            }

            return $weathers;
        } else {
            // Обнаружены ошибки валидации
            foreach ($violations as $violation) {
                $this->logger->warning($violation->getMessage());
                $errors[] = $violation->getMessage();
            }

            return ["errors" => $errors];
        }
    }
}