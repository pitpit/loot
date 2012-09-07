<?php

namespace Pitpit\PostGis\DBAL\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Pitpit\PostGis\DBAL\Point;

/**
 * Only support Postgis for now
 */
class PointType extends Type
{
    const POINT = 'point';

    /**
     *
     * @param array $fieldDeclaration
     * @param AbstractPlatform $platform
     * @return string
     */
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'geography(POINT,'.Point::$SRID.')';
    }

    /**
     *
     * @param type $value
     * @param AbstractPlatform $platform
     * @return Point
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return Point::fromGeoJson($value);
    }

    public function getName()
    {
        return self::POINT;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->toWKT();
    }

    public function canRequireSQLConversion()
    {
        return true;
    }

    public function convertToPHPValueSQL($sqlExpr, $platform)
    {
        return 'ST_AsGeoJSON('.$sqlExpr.') ';
    }

    public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform)
    {
        return $sqlExpr;
    }
}