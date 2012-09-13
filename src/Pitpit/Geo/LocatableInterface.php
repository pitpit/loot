<?php

namespace Pitpit\Geo;

interface LocatableInterface
{
    function setLocation(Point $point);

    function getLocation();
}