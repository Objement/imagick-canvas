<?php

namespace Objement\OmImagickCanvas\Interfaces;

use Imagick;
use Objement\OmImagickCanvas\Models\OmUnit;
use Objement\OmImagickCanvas\OmCanvas;

interface OmElementInterface
{
    /**
     * @param int|null $resolution The resolution in pixel per inch. You can use the OmCanvas::RESOLUTION_* constants.
     * @param int|null $colorSpace Use the OmCanvas::COLORSPACE_* constants.
     * @return Imagick
     */
    public function getImagick(?int $resolution=72, ?int $colorSpace=OmCanvas::COLORSPACE_RGB): Imagick;

    /**
     * @return OmUnit
     */
    public function getWidth(): OmUnit;

    /**
     * @return OmUnit
     */
    public function getHeight(): OmUnit;
}
