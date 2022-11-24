<?php

namespace Objement\OmImagickCanvas\Elements;

use Imagick;
use ImagickDraw;
use ImagickPixel;
use Objement\OmImagickCanvas\Elements\Settings\OmElementRectangleSettings;
use Objement\OmImagickCanvas\Interfaces\OmElementInterface;
use Objement\OmImagickCanvas\Models\OmUnit;
use Objement\OmImagickCanvas\OmCanvas;

class OmElementRectangle implements OmElementInterface
{
    private OmUnit $width;
    private OmUnit $height;
    /**
     * @var OmElementRectangleSettings
     */
    private OmElementRectangleSettings $settings;

    /**
     * OmElementImage constructor.
     * @param string $text
     * @param OmUnit $width
     * @param OmUnit $height
     * @param OmElementRectangleSettings $settings
     */
    public function __construct(OmUnit $width, OmUnit $height, OmElementRectangleSettings $settings)
    {
        $this->width = $width;
        $this->height = $height;
        $this->settings = $settings;
    }

    /**
     * @inheritDoc
     */
    public function getImagick(?int $resolution = 72, ?int $colorSpace = OmCanvas::COLORSPACE_RGB): Imagick
    {
        $im = new Imagick();
        $im->setResolution($resolution, $resolution);

        $color = new ImagickPixel('#' . $this->settings->getColorHex());

        $im->newImage($this->width->toPixel($resolution), $this->height->toPixel($resolution), $color);
        $im->transformImageColorspace(OmCanvas::getImagickColorSpace($colorSpace));

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
