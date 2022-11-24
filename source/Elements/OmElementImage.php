<?php

namespace Objement\OmImagickCanvas\Elements;

use Imagick;
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
     * @param OmUnit|null $width
     * @param OmUnit|null $height
     */
    public function __construct(string $sourceFile, ?OmUnit $width = null, ?OmUnit $height = null)
    {
        $this->sourceFile = $sourceFile;

        $im = new Imagick($this->sourceFile);
        $this->fixRotationDependingOnExifData($im);

        $this->width = $width ?? ($height
            ? OmUnit::create(OmUnit::UNIT_PIXELS, $height->getValue() * ($im->getImageWidth() / $im->getImageHeight()))
            : OmUnit::create(OmUnit::UNIT_PIXELS, $im->getImageWidth())
        );
        $this->height = $height ?? ($width
            ? OmUnit::create(OmUnit::UNIT_PIXELS, $width->getValue() * ($im->getImageHeight() / $im->getImageWidth()))
            : OmUnit::create(OmUnit::UNIT_PIXELS, $im->getImageHeight())
        );

        $im->destroy();
    }

    private function fixRotationDependingOnExifData(Imagick $im)
    {
        switch ($im->getImageOrientation()) {
            default:
            case imagick::ORIENTATION_TOPLEFT:
                break;
            case imagick::ORIENTATION_TOPRIGHT:
                $im->flipImage();
                $im->rotateImage("#000", 180);
                break;
            case imagick::ORIENTATION_BOTTOMRIGHT:
                $im->rotateImage("#000", 180);
                break;
            case imagick::ORIENTATION_BOTTOMLEFT:
                $im->flipImage();
                break;
            case imagick::ORIENTATION_LEFTTOP:
                $im->rotateImage("#000", -90);
                $im->flipImage();
                break;
            case imagick::ORIENTATION_RIGHTTOP:
                $im->rotateImage("#000", 90);
                break;
            case imagick::ORIENTATION_RIGHTBOTTOM:
                $im->rotateImage("#000", 90);
                $im->flipImage();
                break;
            case imagick::ORIENTATION_LEFTBOTTOM:
                $im->rotateImage("#000", -90);
                break;
        }

        $im->setImageOrientation(imagick::ORIENTATION_TOPLEFT);
    }

    /**
     * @inheritDoc
     */
    public function getImagick(?int $resolution = 72, ?int $colorSpace = OmCanvas::COLORSPACE_RGB): Imagick
    {
        $im = new Imagick($this->sourceFile);
        $im->transformImageColorspace(OmCanvas::getImagickColorSpace($colorSpace));
        $im->resizeImage(
            $this->width->toPixel($resolution),
            $this->height->toPixel($resolution),
            imagick::FILTER_HAMMING,
            1
        );

        return $im;
    }

    /**
     * @inheritDoc
     */
    public function getWidth(): OmUnit
    {
        return OmUnit::create($this->width->getUnit(), $this->width->getValue());
    }

    /**
     * @inheritDoc
     */
    public function getHeight(): OmUnit
    {
        return OmUnit::create($this->height->getUnit(), $this->height->getValue());
    }
}
