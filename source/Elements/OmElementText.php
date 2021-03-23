<?php

namespace Objement\OmImagickCanvas\Elements;

use Imagick;
use ImagickDraw;
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

    private Imagick $imagick;

    /**
     * OmElementImage constructor.
     * @param string $text
     * @param OmUnit $width
     * @param OmUnit $height
     * @param OmElementTextSettings $settings
     */
    public function __construct(string $text, ?OmUnit $width, ?OmUnit $height, OmElementTextSettings $settings)
    {
        $this->width = $width ?? OmUnit::auto();
        $this->height = $height ?? OmUnit::auto();
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

        $draw = new ImagickDraw();
        $draw->setResolution($resolution, $resolution);
        $draw->setGravity(imagick::GRAVITY_NORTHWEST);
        $draw->setFont($this->settings->getFontFile());
        $draw->setFontSize($this->settings->getFontSize()->toPixel(72)); // needs to be 72dpi, because Imagick will do the calculation therefore $draw->setResolution is set to the target resolution
        $draw->setFontWeight($this->settings->isBold() ? 600 : 100);


        $fontMetrics = $im->queryFontMetrics($draw, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZÄÖÜ', false);
        $lineHeight = $fontMetrics['textHeight'] + $fontMetrics['descender'];

        $maxWidth = !$this->width->isAuto() ? $this->getWidth()->toPixel($resolution) : 0;
        $maxHeight = !$this->height->isAuto() ? $this->getHeight()->toPixel($resolution) : 0;
        $linesInfo = [];

        $y = 1 + $fontMetrics['descender'];

        foreach (explode("\n", $this->text) as $line) {

            $fontMetrics = $im->queryFontMetrics($draw, $line, false);
            $lineWidth = $fontMetrics['textWidth'] + 2 * $fontMetrics['boundingBox']['x1'];
            if ($this->width->isAuto() && $lineWidth > $maxWidth) {
                $maxWidth = $lineWidth;
            }
            if ($this->height->isAuto()) {
                $maxHeight += $lineHeight;
            }

            $lineInfo = [
                'text' => $line,
                'y' => $y
            ];

            $y += $lineHeight;

            $linesInfo[] = $lineInfo;
        }

        $im->newImage($maxWidth, $maxHeight, new ImagickPixel('transparent'));
        $im->transformImageColorspace(OmCanvas::getImagickColorSpace($colorSpace));

        foreach ($linesInfo as $lineInfo) {
            $im->annotateImage($draw, 0, $lineInfo['y'], 0, $lineInfo['text']);
        }
        
        $this->imagick = $im;

        return $im;
    }

    /**
     * @inheritDoc
     */
    public function getWidth(): OmUnit
    {
        if ($this->width->isAuto())
            return OmUnit::create('px', $this->imagick->getImageWidth());

        return $this->width;
    }

    /**
     * @inheritDoc
     */
    public function getHeight(): OmUnit
    {
        if ($this->height->isAuto())
            return OmUnit::create('px', $this->imagick->getImageHeight());

        return $this->height;
    }
}
