<?php

namespace Skydrop\ShippingRate\Rule;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class ProductsWithTag
{
    use \Skydrop\Validations\ValidationTrait;

    public $items;
    public $tagId;
    public $rule;

    public function __construct($items = [], $options = array())
    {
        $this->validator = v::attribute('items', v::arrayType()->length(1, null))
            ->attribute('tagId', v::notEmpty());

        $this->items = $items;
        $this->tagId = $options['tagId'];
        $this->rule  = $options['rule'];
    }

    public function call()
    {
        $this->validate();

        switch ($this->rule) {
        case 'every':
            return $this->every();
        case 'once':
            return $this->once();
        default:
            return false;
        }
    }

    private function every()
    {
        // tags_by_product.all? { |tags| tags.include?(tag) }
        foreach ($this->items as $item) {
            if (!in_array($this->tagId, $item['tags'])) {
                return false;
            }
        }
        return true;
    }

    private function once()
    {
        // tags_by_product.any? { |tags| tags.include?(tag) }
        foreach ($this->items as $item) {
            if (in_array($this->tagId, $item['tags'])) {
                return true;
            }
        }
        return false;
    }
}
