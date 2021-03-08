<?php

namespace Objement\OmImagickCanvas\Interfaces;

use Imagick;
use Objement\OmImagickCanvas\Models\OmUnit;

interface OmElementInterface
{
    public function getImagick(int $resolution): Imagick;

    /**
     * @return OmUnit
     */
    public function getWidth(): OmUnit;

    /**
     * @return OmUnit
     */
    public function getHeight(): OmUnit;
}
