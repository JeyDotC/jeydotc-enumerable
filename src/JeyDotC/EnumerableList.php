<?php

namespace JeyDotC;

use ArrayObject;

/**
 * Description of List
 *
 * @author jguevara
 */
class EnumerableList implements IList
{

    use ArrayObjectImplementationTrait;
    
    public static function from($collection): IList {
        return new static($collection);
    }

    public static function empty(): IList {
        return new static([]);
    }

    public function __construct($input = array(), int $flags = 0, string $iterator_class = "ArrayIterator") {
        
        $data = $input instanceof IEnumerable ? $input->toArray() : $input;

        $this->implementationDelegate = new ArrayObject($data, $flags, $iterator_class);
    }

    public function add($value) {
        $this->implementationDelegate->append($value);
    }

    public function offsetExists($offset): bool {
        return $this->implementationDelegate->offsetExists($offset);
    }

    public function offsetGet($offset) {
        return $this->implementationDelegate->offsetGet($offset);
    }

    public function offsetSet($offset, $value): void {
        $this->implementationDelegate->offsetSet($offset, $value);
    }

    public function offsetUnset($offset): void {
        $this->implementationDelegate->offsetUnset($offset);
    }

    public function clear() {
        $this->implementationDelegate = new ArrayObject();
    }

}
