<?php

namespace Objement\OmImagickCanvas\Elements;

use Imagick;
use ImagickDraw;
use ImagickException;
use ImagickPixel;
use Objement\OmImagickCanvas\Elements\Settings\OmElementTextSettings;
use Objement\OmImagickCanvas\Interfaces\OmElementInterface;
use Objement\OmImagickCanvas\Models\OmUnit;
use Objement\OmImagickCanvas\OmCanvas;

class OmElementText implements OmElementInterface
{
    private OmUnit $width;
    private OmUnit $height;
    private string $text;
    /**
     * @var OmElementTextSettings
     */
    private OmElementTextSettings $settings;

    /**
     * OmElementImage constructor.
     * @param string $text
     * @param OmUnit $width
     * @param OmUnit $height
     * @param OmElementTextSettings $settings
     */
    public function __construct(string $text, OmUnit $width, OmUnit $height, OmElementTextSettings $settings)
    {
        $this->width = $width;
        $this->height = $height;
        $this->text = $text;
        $this->settings = $settings;
    }

    /**
     * @inheritDoc
     */
    public function getImagick(?int $resolution = 72, ?int $colorSpace = OmCanvas::COLORSPACE_RGB): Imagick
    {
        $im = new Imagick();
        $im->setResolution($resolution, $resolution);
        $im->newImage($this->getWidth()->toPixel($resolution), $this->getHeight()->toPixel($resolution), new ImagickPixel('transparent'));
        $im->transformImageColorspace(OmCanvas::getImagickColorSpace($colorSpace));

        $draw = new ImagickDraw();
        $draw->setResolution($resolution, $resolution);
        $draw->setGravity(imagick::GRAVITY_NORTHWEST);

        $draw->setFont($this->settings->getFontFile());
        $draw->setFontSize($this->settings->getFontSize()->toPixel(72)); // needs to be 72dpi, because Imagick will do the calculation therefore $draw->setResolution is set to the target resolution
        $draw->setFontWeight($this->settings->isBold() ? 600 : 100);

        /* Dump the font metrics, autodetect multiline */
        $fontMetrics = $im->queryFontMetrics($draw, $this->text);

        //$im->setSize($fontMetrics['textWidth'], $fontMetrics['textHeight']);
        $im->annotateImage($draw, 0, 0, 0, $this->text);
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
