<?php

namespace Objement\OmImagickCanvas\Models;

use Objement\OmImagickCanvas\Exceptions\OmUnitUnknownException;

/**
 * Helps working with units by calculating metric units to pixels by respecting the used resolution of the target image.
 * @package Objement\OmImagickCanvas\Models
 */
class OmUnit
{
    const UNIT_AUTO = 'auto';

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
    private string $unit;
    private float $value;

    /**
     * OmUnit constructor.
     * @param string $unit
     * @param float $value
     * @throws OmUnitUnknownException
     */
    public function __construct(string $unit, float $value = 0)
    {
        $unitConstant = self::stringToConstant($unit);
        if (!$unitConstant) {
            throw new OmUnitUnknownException('Unit unknown.');
        }

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
        if (!$unitConstant) {
            return null;
        }

        try {
            return new OmUnit($unitName, $value);
        } catch (OmUnitUnknownException $e) {
            return null;
        }
    }

    /**
     * Create a unit object which size defines automatically.
     */
    public static function auto(): ?OmUnit
    {
        try {
            return new OmUnit('auto', -1);
        } catch (OmUnitUnknownException $e) {
            return null;
        }
    }

    private static function stringToConstant($unitName): ?string
    {
        $units = [
            'auto' => self::UNIT_AUTO,
            'cm' => self::UNIT_CENTIMETERS,
            'mm' => self::UNIT_MILLIMETERS,
            'px' => self::UNIT_PIXELS,
            'pt' => self::UNIT_POINTS
        ];

        if (isset($units[$unitName])) {
            return $units[$unitName];
        }

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

    /**
     * @param float $value Adds the given amount to the value.
     * @return OmUnit
     */
    public function add(float $value): OmUnit
    {
        $this->value += $value;
        return $this;
    }

    /**
     * @param float $value Subtracts the given amount from the value.
     * @return OmUnit
     */
    public function subtract(float $value): OmUnit
    {
        $this->value -= $value;
        return $this;
    }

    /**
     * @param float $value Divides the the value by the given amount.
     * @return OmUnit
     */
    public function divide(float $value): OmUnit
    {
        $this->value /= $value;
        return $this;
    }

    /**
     * @param float $value Multiplies the given amount by the value.
     * @return OmUnit
     */
    public function multiply(float $value): OmUnit
    {
        $this->value *= $value;
        return $this;
    }

    public function toPixel(int $resolution): int
    {
        if ($this->unit == 'auto') {
            return -1;
        }

        switch ($this->unit) {
            default:
                trigger_error('Unknown resolution.', E_USER_ERROR);
            case self::UNIT_PIXELS:
                $pixelValue = $this->value;
                break;
            case self::UNIT_CENTIMETERS:
                $pixelValue = $this->value * ($resolution / 2.54);
                break;
            case self::UNIT_MILLIMETERS:
                $pixelValue = $this->value / 10 * ($resolution / 2.54);
                break;
            case self::UNIT_POINTS:
                $pixelValue = $this->value * ($resolution / 72);
                break;
        }

        return (int)$pixelValue;
    }

    public function __toString()
    {
        if ($this->unit == 'auto') {
            return 'auto';
        }

        return $this->getValue() . $this->getUnit();
    }

    public function isAuto()
    {
        return $this->unit == 'auto';
    }
}
