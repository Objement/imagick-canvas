<?php declare(strict_types=1);

use Objement\OmImagickCanvas\Elements\OmElementComposition;
use Objement\OmImagickCanvas\Elements\OmElementText;
use Objement\OmImagickCanvas\Elements\Settings\OmElementTextSettings;
use Objement\OmImagickCanvas\Models\OmUnit;
use Objement\OmImagickCanvas\OmCanvas;
use PHPUnit\Framework\TestCase;

final class OmElementTextTest extends TestCase
{
    public function testWhenSizesAreSetToAutoTheDimensionsMustBeDefinedByTheText(): void
    {
        $textSettings = new OmElementTextSettings(__DIR__.'/Roboto-Regular.ttf', OmUnit::create('px', 16));
        $text = new OmElementText("That is a\ntest text that\nused multiple lines.", OmUnit::auto(), OmUnit::auto(), $textSettings);
        $text->getImagick();

        $this->assertEquals('138px', (string)$text->getWidth());
        $this->assertEquals('45px', (string)$text->getHeight());
    }
}
