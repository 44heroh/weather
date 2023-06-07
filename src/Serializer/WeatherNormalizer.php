<?php


namespace App\Serializer;

use App\Entity\Weather;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class WeatherNormalizer implements DenormalizerInterface
{
    /**
     * @param mixed $data
     * @param string $type
     * @param string|null $format
     * @return bool
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return Weather::class == $type;
    }

    /**
     * @param mixed $data
     * @param string $type
     * @param string|null $format
     * @param array $context
     * @return Weather|mixed
     * @throws \Exception
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): Weather
    {
        $weather = new Weather();
        if(isset($data["dt_txt"]))
            $weather->setDate(new \DateTime($data["dt_txt"]));
        if(isset($data["main"]["temp"]))
            $weather->setTemperature($data["main"]["temp"]);
        if($data["clouds"]["all"])
            $weather->setClouds($data["clouds"]["all"]);

        return $weather;
    }

}