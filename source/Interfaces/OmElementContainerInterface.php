<?php


namespace Objement\OmImagickCanvas\Interfaces;

use Objement\OmImagickCanvas\Models\OmElementPosition;

interface OmElementContainerInterface
{
    public function addElement(OmElementInterface $element, OmElementPosition $position);

    public function getImage($format);
}
