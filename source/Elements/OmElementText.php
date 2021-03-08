<?php

namespace Objement\OmImagickCanvas\Elements;

use Imagick;
use ImagickException;
use Objement\OmImagickCanvas\Interfaces\OmElementInterface;
use Objement\OmImagickCanvas\Models\OmUnit;

class OmElementText implements OmElementInterface
{
    private $sourceFile;
    private OmUnit $width;
    private OmUnit $height;

    /**
     * OmElementImage constructor.
     * @param $sourceFile
     * @param OmUnit $width
     * @param OmUnit $height
     */
    public function __construct($sourceFile, OmUnit $width, OmUnit $height)
    {
        $this->sourceFile = $sourceFile;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @return Imagick
     * @throws ImagickException
     */
    public function getImagick(int $resolution): Imagick
    {
        $im = new Imagick($this->sourceFile);

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
