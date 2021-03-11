<?php

namespace Objement\OmImagickCanvas\Models;

use Exception;
use Objement\OmImagickCanvas\Exceptions\OmUnitUnknownException;

/**
 * Helps working with units by calculating metric units to pixels by respecting the used resolution of the target image.
 * @package Objement\OmImagickCanvas\Models
 */
class OmUnit
{
    const UNIT_CENTIMETERS = 'cm';
    const UNIT_MILLIMETERS = 'mm';
    /**
     * Standard-Pixel 72dpi
     */
    const UNIT_PIXELS = 'px';

    const UNIT_POINTS = 'pt';

    /**
     * @var string
     */
    private $unit;
    private $value;

    /**
     * OmUnit constructor.
     * @param string $unit
     * @param float $value
     * @throws OmUnitUnknownException
     */
    public function __construct(string $unit, float $value)
    {
        $unitConstant = self::stringToConstant($unit);
        if (!$unitConstant)
            throw new OmUnitUnknownException('Unit unknown.');

        $this->unit = $unit;
        $this->value = $value;
    }

    /**
     * An easy way to retrieve a position object.
     * @param string $unitName
     * @param float $value
     * @return OmUnit|null
     */
    public static function create(string $unitName, float $value): ?OmUnit
    {
        $unitConstant = self::stringToConstant($unitName);
        if (!$unitConstant)
            return null;

        try {
            return new OmUnit($unitName, $value);
        } catch (OmUnitUnknownException $e) {
            return null;
        }
    }

    private static function stringToConstant($unitName): ?string
    {
        $units = [
            'cm' => self::UNIT_CENTIMETERS,
            'mm' => self::UNIT_MILLIMETERS,
            'px' => self::UNIT_PIXELS,
            'pt' => self::UNIT_POINTS
        ];

        if (isset($units[$unitName]))
            return $units[$unitName];

        return null;
    }

    /**
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    public function toPixel(int $resolution): int
    {
        switch ($this->unit) {
            case self::UNIT_PIXELS:
                return $this->value;
            case self::UNIT_CENTIMETERS:
                return $this->value * ($resolution / 2.54);
            case self::UNIT_MILLIMETERS:
                return $this->value / 10 * ($resolution / 2.54);
            case self::UNIT_POINTS:
                return $this->value * ($resolution / 72);
        }
        trigger_error('Unknown resolution.', E_USER_ERROR);
        return -1;
    }
}
