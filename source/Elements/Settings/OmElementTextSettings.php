<?php

namespace Objement\OmImagickCanvas\Elements\Settings;

use Objement\OmImagickCanvas\Models\OmUnit;

class OmElementTextSettings
{
    /**
     * @var string
     */
    private string $fontFile;
    /**
     * @var OmUnit
     */
    private OmUnit $fontSize;
    /**
     * @var float
     */
    private float $lineHeight = 1;

    /**
     * @var bool
     */
    private bool $isBold = false;

    private string $colorR = '0';
    private string $colorG = '0';
    private string $colorB = '0';

    /**
     * OmElementTextSettings constructor.
     * @param string $fontFile
     * @param OmUnit $fontSize
     */
    public function __construct(string $fontFile, OmUnit $fontSize)
    {

        $this->fontFile = $fontFile;
        $this->fontSize = $fontSize;
    }


    /**
     * @return string
     */
    public function getFontFile(): string
    {
        return $this->fontFile;
    }

    /**
     * @return OmUnit
     */
    public function getFontSize(): OmUnit
    {
        return $this->fontSize;
    }

    /**
     * @param bool $isBold
     * @return OmElementTextSettings
     */
    public function setBold(bool $isBold)
    {
        $this->isBold = $isBold;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBold(): bool
    {
        return $this->isBold;
    }

    /**
     * @param float $lineHeight 1 = 100%, 1.2 = 120%
     * @return OmElementTextSettings
     */
    public function setLineHeight(float $lineHeight): OmElementTextSettings
    {
        $this->lineHeight = $lineHeight;
        return $this;
    }

    /**
     * @return float
     */
    public function getLineHeight(): float
    {
        return $this->lineHeight;
    }

    /**
     * @param string $colorHex
     * @return OmElementTextSettings
     */
    public function setColorHex(string $colorHex): OmElementTextSettings
    {
        if (strlen($colorHex) != 6)
            trigger_error('Invalid HEX code.', E_USER_ERROR);

        $r = hexdec($colorHex[0] . $colorHex[1]);
        $g = hexdec($colorHex[2] . $colorHex[3]);
        $b = hexdec($colorHex[4] . $colorHex[5]);
        if ($r > 255 || $g > 255 || $b > 255)
            trigger_error('Invalid HEX code.', E_USER_ERROR);

        $this->colorR = $r;
        $this->colorG = $g;
        $this->colorB = $b;
        return $this;
    }

    /**
     * @return string
     */
    public function getColorHex(): string
    {
        return str_pad(dechex($this->colorR), 2, '0', STR_PAD_LEFT) .
            str_pad(dechex($this->colorG), 2, '0', STR_PAD_LEFT) .
            str_pad(dechex($this->colorB), 2, '0', STR_PAD_LEFT);
    }

    public function setColorRgb($red, $green, $blue): void
    {
        $this->colorR = $red;
        $this->colorG = $green;
        $this->colorB = $blue;
    }

    public function getColorRgb(): string
    {
        return implode(' ', [$this->colorR, $this->colorG, $this->colorB]);
    }
}
