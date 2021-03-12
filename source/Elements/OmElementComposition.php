<?php

namespace Objement\OmImagickCanvas\Elements;

use Exception;
use Imagick;
use ImagickException;
use Objement\OmImagickCanvas\Base\OmElementCompositionBase;
use Objement\OmImagickCanvas\Interfaces\OmElementInterface;
use Objement\OmImagickCanvas\Models\OmElementPosition;
use Objement\OmImagickCanvas\Models\OmUnit;

/**
 * Creates a composition canvas for multiple elements. Without using a maximun width or height, the composition will
 * expand automatically to its contents.
 * @package Objement\OmImagickCanvas\Elements
 */
class OmElementComposition extends OmElementCompositionBase
{
    /**
     * @var OmUnit|null
     */
    private ?OmUnit $maxWidth;
    /**
     * @var OmUnit|null
     */
    private ?OmUnit $maxHeight;

    /**
     * OmElementComposition constructor.
     * @param int $resolution
     * @param int $colorSpace
     * @param OmUnit|null $maxWidth Maximum width of this image. Optional.
     * @param OmUnit|null $maxHeight Maximum height of this image. Optional.
     * @throws ImagickException
     */
    public function __construct(int $resolution, int $colorSpace, ?OmUnit $maxWidth = null, ?OmUnit $maxHeight = null)
    {
        parent::__construct($resolution, $colorSpace, $maxWidth, $maxHeight);

        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getWidth(): OmUnit
    {
        if ($this->maxWidth && $this->width->toPixel($this->resolution) > $this->maxWidth->toPixel($this->resolution))
            return $this->maxWidth;

        return $this->width;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getHeight(): OmUnit
    {
        if ($this->maxHeight && $this->height->toPixel($this->resolution) > $this->maxHeight->toPixel($this->resolution))
            return $this->maxHeight;

        return $this->height;
    }

    protected function generateImageForElement(OmElementInterface $element, OmElementPosition $position): Imagick
    {
        $im = parent::generateImageForElement($element, $position);

        $bottomRightEdgePosition = $position->getPositionForCorner($this->resolution, OmElementPosition::BOTTOMRIGHT, OmUnit::create('px', $im->getImageWidth()), OmUnit::create('px', $im->getImageHeight()));

        if (!$this->width || $bottomRightEdgePosition->getX($this->resolution) > $this->width->toPixel($this->resolution))
            $this->width = OmUnit::create('px', $bottomRightEdgePosition->getX($this->resolution));

        if (!$this->height || $bottomRightEdgePosition->getY($this->resolution) > $this->height->toPixel($this->resolution))
            $this->height = OmUnit::create('px', $bottomRightEdgePosition->getY($this->resolution));

        return $im;
    }
}
