<?php

namespace Skydrop\Validations;

use Respect\Validation\Exceptions\NestedValidationException;

trait ValidationTrait
{
    public function validate()
    {
        if (!$this->validator->validate($this)) {
            try {
                $this->validator->assert(get_class($this));
            } catch(NestedValidationException $e) {
                throw new \Exception($e->getFullMessage());
            }
        }
    }
}
