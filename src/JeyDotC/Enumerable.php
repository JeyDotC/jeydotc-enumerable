<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace JeyDotC;

use ArrayObject;
use Exception;
use Traversable;

/**
 * Description of Enumerable
 *
 * @author jguevara
 */
class Enumerable implements IEnumerable
{
    use ArrayObjectImplementationTrait;
    
    public static function from($collection): Enumerable {
        return new static($collection);
    }

    public static function empty(): Enumerable {
        return new static([]);
    }

    public function __construct($input = array(), int $flags = 0, string $iterator_class = "ArrayIterator") {
        
        $data = $input instanceof IEnumerable ? $input->toArray() : $input;

        $this->implementationDelegate = new ArrayObject($data, $flags, $iterator_class);
    }
}
