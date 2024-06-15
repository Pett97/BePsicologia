<?php

namespace Core\Constants;

class Constants
{
    public static function rootPath(): StringPath
    {
        return new StringPath(dirname(__DIR__));
    }

    public static function teste(): StringPath
    {
        $path = new StringPath(".");
        return $path;
    }

    public static function databasePath(): StringPath
    {
        return self::rootPath()->join("../../database/");
    }
}
