<?php

namespace Objement\OmImagickCanvas\Elements;

use Imagick;
use ImagickException;
use Objement\OmImagickCanvas\Interfaces\OmElementInterface;
use Objement\OmImagickCanvas\Models\OmUnit;
use Objement\OmImagickCanvas\OmCanvas;

class OmElementImage implements OmElementInterface
{
    private string $sourceFile;
    private OmUnit $width;
    private OmUnit $height;

    /**
     * OmElementImage constructor.
     * @param $sourceFile
     * @param OmUnit $width
     * @param OmUnit $height
     */
    public function __construct(string $sourceFile, OmUnit $width, OmUnit $height)
    {
        $this->sourceFile = $sourceFile;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @inheritDoc
     */
    public function getImagick(?int $resolution = 72, ?int $colorSpace = OmCanvas::COLORSPACE_RGB): Imagick
    {
        $im = new Imagick($this->sourceFile);
        $im->transformImageColorspace(OmCanvas::getImagickColorSpace($colorSpace));

        $im->resizeImage(
            $this->getWidth()->toPixel($resolution),
            $this->getHeight()->toPixel($resolution),
            imagick::FILTER_HAMMING,
            1);

        return $im;
    }

    /**
     * @inheritDoc
     */
    public function getWidth(): OmUnit
    {
        return $this->width;
    }

    /**
     * @inheritDoc
     */
    public function getHeight(): OmUnit
    {
        return $this->height;
    }
}
