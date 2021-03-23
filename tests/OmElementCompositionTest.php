<?php declare(strict_types=1);

use Objement\OmImagickCanvas\Elements\OmElementComposition;
use Objement\OmImagickCanvas\Elements\OmElementText;
use Objement\OmImagickCanvas\Elements\Settings\OmElementTextSettings;
use Objement\OmImagickCanvas\Models\OmElementPosition;
use Objement\OmImagickCanvas\Models\OmUnit;
use Objement\OmImagickCanvas\OmCanvas;
use PHPUnit\Framework\TestCase;

final class OmElementCompositionTest extends TestCase
{
    public function testDimensionsOfCompositionDependOnTheElementsItContains(): void
    {
        $composition = new OmElementComposition(
            OmCanvas::RESOLUTION_DEFAULT_SCREEN,
            OmCanvas::COLORSPACE_RGB,
            OmUnit::create('px', 400),
            OmUnit::create('px', 400)
        );
        $textSettings = new OmElementTextSettings('Arial', OmUnit::create('px', 16));
        $text = new OmElementText("That is a\ntest text that\nused multiple lines.", OmUnit::auto(), OmUnit::auto(), $textSettings);

        $composition->addElement($text, OmElementPosition::create('px', 0, 0));
        $composition->getImagick();

        $this->assertEquals('139px', (string)$composition->getWidth());
        $this->assertEquals('42px', (string)$composition->getHeight());
    }
}
