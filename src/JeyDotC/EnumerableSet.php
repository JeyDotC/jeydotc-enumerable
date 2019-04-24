<?php

namespace JeyDotC;

use ArrayObject;

/**
 * Description of EnumerableSet
 *
 * @author jguevara
 */
class EnumerableSet implements ISet
{

    use ArrayObjectImplementationTrait;

    /**
     *
     * @var callable
     */
    private $equals;

    public function __construct(callable $equalityComparer = null, $input = array(), int $flags = 0, string $iterator_class = "ArrayIterator") {
        $this->equals = $equalityComparer ?? function($value1, $value2) {
            $value1IsObject = is_object($value1);
            $value2IsObject = is_object($value2);
            
            return ($value1IsObject == $value2IsObject) && ($value1 == $value2);
        };

        $data = $input instanceof IEnumerable ? $input->toArray() : $input;

        $this->implementationDelegate = new ArrayObject([], $flags, $iterator_class);

        foreach ($data as $key => $value) {
            if(!$this->contains($value)){
                $this->implementationDelegate[$key] = $value;
            }
        }
    }
    
    public static function from($data, callable $equalityComparer = null){
        return new static($equalityComparer, $data);
    }
    
    public static function empty(callable $equalityComparer = null){
        return new static($equalityComparer);
    }

    public function add($value) {
        if (!$this->contains($value)) {
            $this->implementationDelegate[] = $value;
        }
    }

    public function clear() {
        $this->implementationDelegate = new ArrayObject();
    }

    private function contains($value) {
        return $this->any(function($value1) use($value) {
                    return ($this->equals)($value1, $value);
                });
    }

}
