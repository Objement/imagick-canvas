<?php

namespace Objement\OmImagickCanvas\Base;

use Imagick;
use ImagickException;
use Objement\OmImagickCanvas\Interfaces\OmElementContainerInterface;
use Objement\OmImagickCanvas\Interfaces\OmElementInterface;
use Objement\OmImagickCanvas\Models\OmElementDrawMeta;
use Objement\OmImagickCanvas\Models\OmElementPosition;
use Objement\OmImagickCanvas\Models\OmUnit;
use Objement\OmImagickCanvas\OmCanvas;

abstract class OmElementCompositionBase implements OmElementContainerInterface, OmElementInterface
{
    /**
     * Default resolution of 72 pixels per inch. It is a default value for normal computer displays. For 4K
     * Displays you should use 144.
     */
    const RESOLUTION_DEFAULT_SCREEN = 72;
    /**
     * Resolution for standard 4K displays.
     */
    const RESOLUTION_4K_SCREEN = 144;
    /**
     * An often used resolution used for printed products.
     */
    const RESOLUTION_DEFAULT_PRINT = 300;

    /**
     * Use this colorspace when your image will be displayed on computer/smartphone screen. RGB (red/green/blue) is
     * used, when the target device generates a color by using the three base colors and lighting it up.
     */
    const COLORSPACE_RGB = 1;
    /**
     * Use this colorspace when your image will be printed on paper or anything that doesn't produce real light.
     */
    const COLORSPACE_CMYK = 2;

    protected Imagick $imagickCanvas;
    protected int $colorSpace;
    protected int $resolution;
    protected ?OmUnit $width;
    protected ?OmUnit $height;
    /**
     * @var OmElementDrawMeta[]
     */
    protected array $elementLayers;

    /**
     * OmElementCompositionBase constructor.
     * @param int $resolution
     * @param int $colorSpace
     * @param OmUnit|null $width
     * @param OmUnit|null $height
     * @throws ImagickException
     */
    protected function __construct(int $resolution, int $colorSpace, ?OmUnit $width = null, ?OmUnit $height = null)
    {
        $imagickCanvas = new Imagick();

        $this->imagickCanvas = $imagickCanvas;
        $this->colorSpace = $colorSpace;
        $this->resolution = $resolution;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @inheritDoc
     */
    public function getImagick(?int $resolution=72, ?int $colorSpace=OmCanvas::COLORSPACE_RGB): Imagick
    {
        $this->generateImage();

        return $this->imagickCanvas;
    }

    /**
     * Adds an element to the canvas.
     * @param OmElementInterface $element
     * @param OmElementPosition $position
     */
    public function addElement(OmElementInterface $element, OmElementPosition $position)
    {
        $this->elementLayers[] = new OmElementDrawMeta($element, $position);
    }

    protected function generateImage(): void
    {
        $generatedSubImages = [];

        foreach ($this->elementLayers as $elementLayer) {

            $element = $elementLayer->getElement();
            $position = $elementLayer->getPosition();

            $im = $this->generateImageForElement($element, $position);

            $posX = $position->getX($this->resolution, $element->getWidth(), $this->width);
            $posY = $position->getY($this->resolution, $element->getHeight(), $this->height);

            $generatedSubImages[] = [
                'position' => ['x' => $posX, 'y' => $posY],
                'imagick' => $im
            ];
        }

        $this->imagickCanvas->setResolution($this->resolution, $this->resolution);
        $this->imagickCanvas->newImage($this->width->toPixel($this->resolution), $this->height->toPixel($this->resolution), 'white');
        $this->imagickCanvas->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);


        $imagickTargetColorspace = self::getImagickColorSpace($this->colorSpace);
        $this->imagickCanvas->transformImageColorspace($imagickTargetColorspace);

        foreach ($generatedSubImages as $image) {
            $this->imagickCanvas->compositeImage($image['imagick'], Imagick::COMPOSITE_OVER, $image['position']['x'], $image['position']['y']);
        }
    }

    /**
     * Converts the OmCanvas-Colorspace constants to the imagick ones.
     * @param int $colorSpace
     * @return int
     */
    public static function getImagickColorSpace(int $colorSpace)
    {
        switch ($colorSpace) {
            case self::COLORSPACE_CMYK:
                $imColorSpace = Imagick::COLORSPACE_CMYK;
                break;
            case self::COLORSPACE_RGB:
            default:
                $imColorSpace = Imagick::COLORSPACE_RGB;
                break;
        }
        return $imColorSpace;
    }

    protected function generateImageForElement(OmElementInterface $element, OmElementPosition $position): Imagick
    {
        $im = $element->getImagick($this->resolution, $this->colorSpace);
        $im->transformImageColorspace($this->getImagickColorSpace($this->colorSpace));

        return $im;
    }
}
