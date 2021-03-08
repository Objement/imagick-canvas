<?php


namespace Objement\OmImagickCanvas;

use Imagick;
use ImagickException;
use Objement\OmImagickCanvas\Base\OmElementCompositionBase;
use Objement\OmImagickCanvas\Elements\OmElementComposition;
use Objement\OmImagickCanvas\Models\OmUnit;

/**
 * The canvas, which is the base of your image. Just add your images, texts or shapes using the methods it provides.
 * @package Objement\OmImagickCanvas
 */
class OmCanvas extends OmElementCompositionBase
{
    /**
     * Canvas constructor.
     * @param int $resolution Use constants OmCanvas::RESOLUTION_*
     * @param int $colorSpace Use constants OmCanvas::COLORSPACE_*
     * @param OmUnit $width The width of the canvas
     * @param OmUnit $height The height of the canvas
     * @throws ImagickException Exception is probably only thrown, when the extension Imagick isn't enabled on the server.
     */
    public function __construct(int $resolution, int $colorSpace, OmUnit $width, OmUnit $height)
    {
        parent::__construct($resolution, $colorSpace, $width, $height);
    }

    /**
     * Returns an composition element.
     * @return OmElementComposition|null
     */
    public function createCompositionElement(): ?OmElementComposition
    {
        try {
            $composition = new OmElementComposition($this->resolution, $this->colorSpace);
        } catch (ImagickException $e) {
            return null;
        }
        return $composition;
    }

    /**
     * Returns the generated image in the specified format.
     * @param $format
     * @return Imagick
     * @throws ImagickException
     */
    public function getImage($format)
    {
        $im = $this->getImagick($this->resolution);

        $im->setImageFormat($format);
        return $im;
    }
}
