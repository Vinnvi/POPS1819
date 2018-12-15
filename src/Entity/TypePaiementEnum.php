<?php

namespace App\Entity;

abstract class TypePaiementEnum
{
    const TRANSPORT    = "Transport";
    const HOTEL = "Hotel";
    const REPAS = "Repas";
    const AUTRE  = "autre";

    /** @var array user friendly named type */
    protected static $typeName = [
        self::TRANSPORT    => 'Transport',
        self::HOTEL => 'Hotel',
        self::REPAS => 'Repas',
        self::AUTRE  => 'autre',
    ];

    /**
     * @param  string $typeShortName
     * @return string
     */
    public static function getTypeName($typeShortName)
    {
        if (!isset(static::$typeName[$typeShortName])) {
            return "Unknown type ($typeShortName)";
        }

        return static::$typeName[$typeShortName];
    }

    /**
     * @return array<string>
     */
    public static function getAvailableTypes()
    {
        return [
            self::TRANSPORT,
            self::HOTEL,
            self::REPAS,
            self::AUTRE,
        ];
    }
}
