<?php

namespace JeyDotC;

use TheSeer\Tokenizer\Exception;
use Traversable;

/**
 *
 * @author jguevara
 */
trait ArrayObjectImplementationTrait
{

    /**
     *
     * @var \ArrayObject 
     */
    private $implementationDelegate;

    /**
     * {@inheritDoc}
     */
    public function select(callable $callable): IEnumerable {
        $array = $this->implementationDelegate->getArrayCopy();
        $keys = array_keys($array);
        $values = array_values($array);
        $result = array_map($callable, $values, $keys);

        return self::from(array_combine($keys, $result));
    }

    /**
     * {@inheritDoc}
     */
    public function where(callable $condition): IEnumerable {
        $array = $this->implementationDelegate->getArrayCopy();

        return self::from(array_filter($array, $condition, ARRAY_FILTER_USE_BOTH));
    }

    /**
     * {@inheritDoc}
     */
    public function keys(): IEnumerable {
        $array = $this->implementationDelegate->getArrayCopy();
        $keys = array_keys($array);
        return self::from($keys);
    }

    /**
     * {@inheritDoc}
     */
    public function values(): IEnumerable {
        $array = $this->implementationDelegate->getArrayCopy();
        $values = array_values($array);
        return self::from($values);
    }

    /**
     * {@inheritDoc}
     */
    public function groupBy(callable $discriminator): IEnumerable {
        $array = $this->implementationDelegate->getArrayCopy();
        $resultArray = [];

        foreach ($array as $key => $value) {
            $newKey = $discriminator($value, $key);

            if (!array_key_exists($newKey, $resultArray)) {
                $resultArray[$newKey] = [];
            }

            $resultArray[$newKey][$key] = $value;
        }

        return Enumerable::from($resultArray);
    }

    /**
     * {@inheritDoc}
     */
    function orderBy(callable $orderCallback, string $direction = 'asc'): IEnumerable {
        $array = $this->implementationDelegate->getArrayCopy();

        // TODO: Provide keys, somehow...
        if ($direction == 'desc') {
            uasort($array, function($object1, $object2) use($orderCallback) {
                return -($orderCallback($object1, $object2));
            });
        } else {
            uasort($array, $orderCallback);
        }

        return Enumerable::from($array);
    }

    /**
     * {@inheritDoc}
     */
    function ofType(string $className): IEnumerable {
        return $this->where(function($item) use($className) {
                    return $item instanceof $className;
                });
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array {
        return $this->implementationDelegate->getArrayCopy();
    }

    /**
     * {@inheritDoc}
     */
    public function selectMany(callable $callback): IEnumerable {
        $results = [];

        $selectedValues = $this->select($callback);

        foreach ($selectedValues as $innerCollectionCandidate) {
            if (is_array($innerCollectionCandidate) || $innerCollectionCandidate instanceof Traversable) {
                foreach ($innerCollectionCandidate as $value) {
                    $results[] = $value;
                }
            } else {
                $results[] = $innerCollectionCandidate;
            }
        }

        return Enumerable::from($results);
    }

    /**
     * {@inheritDoc}
     */
    public function union($otherCollection, callable $comparison = null): IEnumerable {
        $base = $this->implementationDelegate->getArrayCopy();
        $equalityComparer = $comparison ?? function($right, $left) {
            return $right == $left;
        };

        foreach ($otherCollection as $otherValue) {
            if (!$this->any(function($item) use($equalityComparer, $otherValue) {
                        return $equalityComparer($item, $otherValue);
                    })) {
                $base[] = $otherValue;
            }
        }

        return Enumerable::from($base);
    }

    /**
     * {@inheritDoc}
     */
    public function any(callable $predicate = null): bool {
        $contents = $this->implementationDelegate->getArrayCopy();

        if ($predicate == null) {
            return !empty($contents);
        }

        foreach ($contents as $key => $value) {
            if ($predicate($value, $key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function all(callable $predicate): bool {
        return !$this->any(function($value, $key) use($predicate) {
                    return !$predicate($value, $key);
                });
    }

    /**
     * {@inheritDoc}
     */
    public function concat($otherCollection): IEnumerable {
        $contents = $this->implementationDelegate->getArrayCopy();

        foreach ($otherCollection as $value) {
            $contents[] = $value;
        }

        return Enumerable::from($contents);
    }

    /**
     * {@inheritDoc}
     */
    public function extend($otherCollection): IEnumerable {
        $contents = $this->implementationDelegate->getArrayCopy();

        foreach ($otherCollection as $key => $value) {
            $contents[$key] = $value;
        }

        return Enumerable::from($contents);
    }

    /**
     * {@inheritDoc}
     */
    public function first(callable $predicate = null) {
        if (!$this->any()) {
            throw new Exception("Can't find the first value of an empty collection.");
        }

        $contents = $this->implementationDelegate->getArrayCopy();

        if ($predicate == null) {
            return reset($contents);
        }

        foreach ($contents as $key => $value) {
            if ($predicate($value, $key)) {
                return $value;
            }
        }

        throw new Exception("No object in this collection fulfills the given predicate.");
    }

    /**
     * {@inheritDoc}
     */
    public function firstOrDefault($default = null, callable $predicate = null) {
        if (!$this->any()) {
            return $default;
        }

        $contents = $this->implementationDelegate->getArrayCopy();

        if ($predicate == null) {
            return reset($contents);
        }

        foreach ($contents as $key => $value) {
            if ($predicate($value, $key)) {
                return $value;
            }
        }

        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function last(callable $predicate = null) {
        if (!$this->any()) {
            throw new Exception("Can't find the first value of an empty collection.");
        }

        $contents = $this->implementationDelegate->getArrayCopy();

        if ($predicate == null) {
            return end($contents);
        }

        foreach (array_reverse($contents) as $key => $value) {
            if ($predicate($value, $key)) {
                return $value;
            }
        }

        throw new Exception("No object in this collection fulfills the given predicate.");
    }

    /**
     * {@inheritDoc}
     */
    public function lastOrDefault($default = null, callable $predicate = null) {
        if (!$this->any()) {
            return $default;
        }

        $contents = $this->implementationDelegate->getArrayCopy();

        if ($predicate == null) {
            return end($contents);
        }

        foreach (array_reverse($contents) as $key => $value) {
            if ($predicate($value, $key)) {
                return $value;
            }
        }

        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function take(int $count): IEnumerable {
        $data = $this->implementationDelegate->getArrayCopy();

        $result = array_slice($data, 0, $count, true);

        return Enumerable::from($result);
    }
    
    /**
     * {@inheritDoc}
     */
    public function each(callable $callback): void{
        foreach ($this->implementationDelegate as $key => $value){
            $callback($value, $key);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function skip(int $count): IEnumerable {
        $data = $this->implementationDelegate->getArrayCopy();

        $result = array_slice($data, $count, null, true);

        return Enumerable::from($result);
    }

    public function count(): int {
        return $this->implementationDelegate->count();
    }

    public function getIterator(): Traversable {
        return $this->implementationDelegate->getIterator();
    }

    public function serialize(): string {
        return $this->implementationDelegate->serialize();
    }

    public function unserialize($serialized): void {
        $this->implementationDelegate = new \ArrayObject();
        $this->implementationDelegate->unserialize($serialized);
    }

}
