<?php

namespace Objement\OmImagickCanvas\Elements;

use Imagick;
use ImagickDraw;
use ImagickException;
use ImagickPixel;
use Objement\OmImagickCanvas\Elements\Settings\OmElementTextSettings;
use Objement\OmImagickCanvas\Interfaces\OmElementInterface;
use Objement\OmImagickCanvas\Models\OmUnit;

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
     * @return Imagick
     * @throws ImagickException
     */
    public function getImagick(int $resolution): Imagick
    {
        /* Create a new Imagick object */
        $im = new Imagick();
        $im->setResolution($resolution,$resolution);
        $im->newImage($this->getWidth()->toPixel($resolution), $this->getHeight()->toPixel($resolution), new ImagickPixel('transparent'));

        $draw = new ImagickDraw();
        $draw->setResolution($resolution,$resolution);

        /* Set the font */
        $draw->setFont($this->settings->getFontFile());
        $draw->setFontSize($this->settings->getFontSize()->toPixel(72)); // needs to be 72dpi, because Imagick will do the calculation because of $draw->setResolution
        $draw->setGravity(imagick::GRAVITY_NORTHWEST);

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
