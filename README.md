# imagick-canvas
PHP-Library to easily generate Imagick graphics containing pictures, texts and shapes by writing object-oriented PHP code.

## Installation
The library is available on Packagist and can therefore be installed using Composer.

```
composer require objement/imagick-canvas
```

## Examples

### Create a centered watermark image
````PHP
$image = new OmElementImage($filepath);
$canvas = new OmCanvas(
	OmCanvas::RESOLUTION_DEFAULT_SCREEN, // for web (resolution is important when working with texts)
	OmCanvas::COLORSPACE_RGB, // for web RGB, use CMYK for print
	$image->getWidth(), $image->getHeight()
);
$canvas->addElement($image, OmElementPosition::create(OmUnit::UNIT_PIXELS, 0, 0));
$watermark = new OmElementImage(realpath('watermark.png'), $image->getWidth()->divide(5));
$canvas->addElement($watermark, OmElementPosition::create(OmUnit::UNIT_PIXELS,
	$image->getWidth()->divide(2)->subtract($watermark->getWidth()->divide(2)->getValue())->getValue(), // center it: (image width / 2) - (watermark width / 2)
	$image->getHeight()->divide(2)->subtract($watermark->getHeight()->divide(2)->getValue())->getValue()
));

$canvas->getImage('png')->writeImage($filepath);
````