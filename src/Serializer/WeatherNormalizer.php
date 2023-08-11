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
        return Weather::class == $type || isset($data["list"]);
    }

    /**
     * @param mixed $data
     * @param string $type
     * @param string|null $format
     * @param array $context
     * @return Weather|mixed
     * @throws \Exception
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): array|Weather
    {
        if(isset($data["list"])) {
            $weathers = $this->denormalizeToArrayObjects($data);
            return $weathers;
        } else {
            $weather = $this->denormalizeToObject($data);
            return $weather;
        }
    }

    /**
     * @param mixed $data
     */
    public function denormalizeToArrayObjects(mixed $data): array
    {
        if(isset($data["list"])) {
            $weathers = [];
            foreach($data["list"] as $key => $value) {
                $weathers[] = self::denormalizeToObject($value);
            }

            return $weathers;
        }
    }

    /**
     * @param mixed $data
     * @return Weather
     * @throws \Exception
     */
    public function denormalizeToObject(mixed $data): Weather
    {
        $weather = new Weather();
        if(isset($data["dt_txt"]))
            $weather->setDate(new \DateTime($data["dt_txt"]));
        if(isset($data["main"]["temp"]))
            $weather->setTemperature($data["main"]["temp"]);
        if(isset($data["clouds"]["all"]))
            $weather->setClouds($data["clouds"]["all"]);
        if(isset($data["visibility"]))
            $weather->setVisibility($data["visibility"]);
        if(isset($data["pop"]))
            $weather->setPop($data["pop"]);

        return $weather;
    }

}