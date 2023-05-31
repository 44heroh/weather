<?php


namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Класс взаимодействия с api OpenWeather
 * Class OpenWeatherApiService
 * @package App\Service
 */
class OpenWeatherApiService
{
    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * OpenWeatherApiService constructor.
     * @param ParameterBagInterface $params
     */
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * @param array $options
     */
    public function request(array $options) {
        if (!isset($options['lat']) || !isset($options['lon']))
            throw new \Exception("not found lat or lon");

        if($this->params->get('app.open.weather.forecast.url') == "")
            throw new \Exception("not found url");

        if($this->params->get('app.open.weather.appid') == "")
            throw new \Exception("not found appid");

        $url = $this->params->get('app.open.weather.forecast.url');
        $appId = $this->params->get('app.open.weather.appid');

        $url = $url . "?" . http_build_query($options) . "&appid=" . $appId . "&units=metric";
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response);
    }
}