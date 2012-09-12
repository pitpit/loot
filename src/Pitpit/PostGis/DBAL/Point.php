<?php

namespace Pitpit\PostGis\DBAL;

class Point
{
    private $latitude;
    private $longitude;
    public static $SRID = '4326';

    public function __construct($longitude, $latitude)
    {
        $this->lat = $latitude;
        $this->longitude = $longitude;

        if (!(($longitude > -180 && $longitude < 180) && ($latitude > -90 && $latitude < 90))) {
            throw new \OutOfRangeException('Invalid longitude or latitude');
        }
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function toGeoJson()
    {
        $array = array("type" => "Point", "coordinates" => array ($this->longitude, $this->latitude));

        return \json_encode($array);
    }

    /**
     *
     * @return string
     */
    public function toWKT()
    {
        return 'SRID='.self::$SRID.';POINT('.$this->longitude.' '.$this->latitude.')';
    }

    /**
     * Create a Point from a GEO Json
     * @param string $geojson
     * @return Point
     */
    public static function fromGeoJson($geojson)
    {
        $a = json_decode($geojson);
        //check if the geojson string is correct
        if ($a == null or !isset($a->type) or !isset($a->coordinates)) {
            throw new \InvalidArgumentException('Invalid geo json');
        }

        if ($a->type != "Point") {
            throw new \InvalidArgumentException('Invalid geo type');
        } else {
            $latitude = $a->coordinates[0];
            $longitude = $a->coordinates[1];

            return new self($lon, $latitude);
        }
    }
}