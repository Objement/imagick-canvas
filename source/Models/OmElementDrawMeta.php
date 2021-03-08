<?php

namespace Objement\OmImagickCanvas\Models;

use Objement\OmImagickCanvas\Interfaces\OmElementInterface;

/**
 * Represents an element that should be drawn inside a canvas with the needed meta data, such as its position.
 * @package Objement\OmImagickCanvas\Models
 */
class OmElementDrawMeta
{
    private OmElementInterface $element;
    private OmElementPosition $position;

    /**
     * OmElementData constructor.
     * @param OmElementInterface $element
     * @param OmElementPosition $position
     */
    public function __construct(OmElementInterface $element, OmElementPosition $position)
    {
        $this->element = $element;
        $this->position = $position;
    }

    /**
     * @return OmElementInterface
     */
    public function getElement(): OmElementInterface
    {
        return $this->element;
    }

    /**
     * @return OmElementPosition
     */
    public function getPosition(): OmElementPosition
    {
        return $this->position;
    }
}
