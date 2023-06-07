<?php


namespace App\Objects;


class Coord
{
    /**
     * @var float
     */
    private float $lat;
    /**
     * @var float
     */
    private float $lon;

    /**
     * Coord constructor.
     * @param float $lat
     * @param float $lon
     */
    public function __construct($lat, $lon)
    {
        $this->lat = $lat;
        $this->lon = $lon;
    }

    /**
     * @return float
     */
    public function getLat(): float
    {
        return $this->lat;
    }

    /**
     * @return float
     */
    public function getLon(): float
    {
        return $this->lon;
    }
}