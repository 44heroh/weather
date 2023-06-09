<?php


namespace App\Config;


use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class WeatherParams
{
    private string $url;
    private string $appid;

    /**
     * WeatherParams constructor.
     * @param $url
     * @param $appid
     */
    public function __construct($url, $appid)
    {
        $this->url = $url;
        $this->appid = $appid;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getAppid()
    {
        return $this->appid;
    }

    /**
     * @param string $appid
     */
    public function setAppid($appid)
    {
        $this->appid = $appid;
    }

}