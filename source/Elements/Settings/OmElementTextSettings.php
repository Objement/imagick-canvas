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

    private OmUnit $lineHeight;

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
     * @return OmUnit
     */
    public function getLineHeight(): OmUnit
    {
        return $this->lineHeight;
    }

    /**
     * @param OmUnit $lineHeight
     * @return OmElementTextSettings
     */
    public function setLineHeight(OmUnit $lineHeight): self
    {
        $this->lineHeight = $lineHeight;
        return $this;
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
}
