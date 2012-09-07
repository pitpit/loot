<?php

namespace Pitpit\PostGis\DBAL;

class Point
{
    private $lat;
    private $lon;
    public static $SRID = '4326';

    public function __construct($lon, $lat)
    {
        $this->lat = $lat;
        $this->lon = $lon;

        if (!(($lon > -180 && $lon < 180) && ($lat > -90 && $lat < 90))) {
            throw new \OutOfRangeException('Invalid longitude or latitude');
        }
    }

    public function toGeoJson()
    {
        $array = array("type" => "Point", "coordinates" => array ($this->lon, $this->lat));

        return \json_encode($array);
    }

    /**
     *
     * @return string
     */
    public function toWKT()
    {
        return 'SRID='.self::$SRID.';POINT('.$this->lon.' '.$this->lat.')';
    }

    /**
     *
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
            $lat = $a->coordinates[0];
            $lon = $a->coordinates[1];

            return new self($lon, $lat);
        }
    }
}