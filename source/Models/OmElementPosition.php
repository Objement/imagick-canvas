<?php

namespace Objement\OmImagickCanvas\Models;

/**
 * Describes the position of an element.
 * @package Objement\OmImagickCanvas\Models
 */
class OmElementPosition
{
    const TOPLEFT = 1;
    const TOPRIGHT = 2;
    const BOTTOMLEFT = 4;
    const BOTTOMRIGHT = 8;

    private OmUnit $x;
    private OmUnit $y;
    private ?int $relativeTo;
    private ?int $align;

    /**
     * OmElementPosition constructor.
     * @param OmUnit $x
     * @param OmUnit $y
     * @param int $relativeTo
     * @param int $align
     */
    public function __construct(OmUnit $x, OmUnit $y, ?int $relativeTo = self::TOPLEFT, ?int $align = self::TOPLEFT)
    {
        $this->x = $x;
        $this->y = $y;
        $this->relativeTo = $relativeTo;
        $this->align = $align;
    }

    /**
     * An easy way to retrieve a position object.
     * @param $unitName
     * @param $x
     * @param $y
     * @param int|null $relativeTo
     * @param int|null $align
     * @return OmElementPosition|null
     */
    public static function create($unitName, $x, $y, ?int $relativeTo = self::TOPLEFT, ?int $align = self::TOPLEFT): ?OmElementPosition
    {
        $unitX = OmUnit::create($unitName, $x);
        $unitY = OmUnit::create($unitName, $y);

        return new OmElementPosition($unitX, $unitY, $relativeTo, $align);
    }

    /**
     * @param int $resolution
     * @param OmUnit|null $width
     * @param OmUnit|null $containerWidth
     * @return int
     */
    public function getX(int $resolution, ?OmUnit $width = null, ?OmUnit $containerWidth = null): int
    {
        if ($containerWidth !== null) {
            switch ($this->relativeTo) {
                case self::BOTTOMLEFT:
                case self::TOPLEFT:
                    return $this->x->toPixel($resolution);
                    break;
                case self::BOTTOMRIGHT:
                case self::TOPRIGHT:
                    return $containerWidth->toPixel($resolution) - $width->toPixel($resolution) - $this->x->toPixel($resolution);
                    break;
            }
        }

        return $this->x->toPixel($resolution);
    }

    /**
     * @param int $resolution
     * @param OmUnit|null $height
     * @param OmUnit|null $containerHeight
     * @return int
     */
    public function getY(int $resolution, ?OmUnit $height = null, ?OmUnit $containerHeight = null): int
    {
        if ($containerHeight !== null) {
            switch ($this->relativeTo) {
                case self::TOPLEFT:
                case self::TOPRIGHT:
                    return $this->y->toPixel($resolution);
                    break;
                case self::BOTTOMLEFT:
                case self::BOTTOMRIGHT:
                    return $containerHeight->toPixel($resolution) - $height->toPixel($resolution) - $this->y->toPixel($resolution);
                    break;
            }
        }

        return $this->y->toPixel($resolution);
    }

    /**
     * @param int $resolution
     * @param int $corner
     * @param OmUnit $elementWidth
     * @param OmUnit $elementHeight
     * @return OmElementPosition ?OmElementPosition
     */
    public function getPositionForCorner(int $resolution, int $corner, OmUnit $elementWidth, OmUnit $elementHeight): OmElementPosition
    {
        switch ($corner) {
            case self::TOPLEFT:
                return OmElementPosition::create(
                    'px',
                    $this->getX($resolution, $elementWidth),
                    $this->getY($resolution, $elementHeight)
                );
            case self::TOPRIGHT:
                return OmElementPosition::create(
                    'px',
                    $this->getX($resolution, $elementWidth) + $elementWidth->toPixel($resolution),
                    $this->getY($resolution, $elementHeight)
                );
            case self::BOTTOMLEFT:
                return OmElementPosition::create(
                    'px',
                    $this->getX($resolution, $elementWidth),
                    $this->getY($resolution, $elementHeight) + $elementHeight->toPixel($resolution)
                );
            case self::BOTTOMRIGHT:
                return OmElementPosition::create('px',
                    $this->getX($resolution, $elementWidth) + $elementWidth->toPixel($resolution),
                    $this->getY($resolution, $elementHeight) + $elementHeight->toPixel($resolution)
                );
                break;
        }

        return null;
    }
}
