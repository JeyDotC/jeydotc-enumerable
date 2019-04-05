<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace JeyDotC;

/**
 * Represents a collection with some convenience methods like 'select' or 'where'.
 * 
 * Most methods will return a new instance of this collection, allowing to have
 * a fluent interface and keep the original collection unmodified, which is ideal
 * for most use cases.
 * 
 * Interface contract notes:
 * 1. Implementations shall not modify the original collection.
 * 2. Implementations should always return a new instance of IEnumerable.
 * 3. In general, keys association should be kept, unless explicitly stated (@see IEnumerable::selectMany($callback) and most methods that involve a second collection). 
 * 4. Any callback function that expects to receive value and key, should always receive the value first and the key later: function($value, $key){}
 * 
 * @author jguevara
 */
interface IEnumerable extends \IteratorAggregate, \Serializable, \Countable
{

    /**
     * Gets all elements within this enumerable that fulfill the given condition.
     * 
     * @param callable $condition A function that will receive the value as the first parameter and, optionally, the key as the second parameter. It must return true to include the given objet in the results or false to exclude it.
     * @return \Framework\IEnumerable
     */
    public function where(callable $condition): IEnumerable;

    /**
     * Applies a function to each element in this enumerable and creates a new enumerable with the results of each function call.
     * 
     * @param callable $callable A function that will receive the value as the first parameter and, optionally, the key as the second parameter. The return value is added to the resulting enumerable.
     * @return \Framework\IEnumerable 
     */
    public function select(callable $callable): IEnumerable;

    /**
     * Gets the keys of this enumerable (non-recursive).
     * 
     * @return \Framework\IEnumerable
     */
    public function keys(): IEnumerable;

    /**
     * Gets the values of this enumerable (non-recursive).
     * 
     * @return \Framework\IEnumerable
     */
    public function values(): IEnumerable;

    /**
     * Groups the elements of this enumerable by the given discriminator function. The returned enumerable will have the discriminator values as keys, and arrays of objects for value.
     * 
     * @param callable $discriminator A function  that will receive the value and key, and that must return a value to classify the given object. The returned value will be used as array key.
     * @return \Framework\IEnumerable
     */
    public function groupBy(callable $discriminator): IEnumerable;

    /**
     * Orders this enumerable by the given callback function.
     * 
     * @param callable $orderCallback A callable that will receive two values to compare (no keys) and that should return 0 if the two objects are equal, 1 if the first parameter is greater than second one, and -1 if second parameter is greater than first.
     * @param string $direction Either asc or desc to represent sorting in ascending or descending order respectively
     * @return \Framework\IEnumerable
     */
    public function orderBy(callable $orderCallback, string $direction = 'asc'): IEnumerable;

    /**
     * Returns the objects that are instances of the given class.
     * 
     * @param string $className The fully qualified class name the values must be instance of.
     * @return \Framework\IEnumerable
     */
    public function ofType(string $className): IEnumerable;

    /**
     * Applies a function to each element in this enumerable and creates a new enumerable with the results of each function call, except that any returned array or \Traversable elements will be appended to the resulting array. NOTE: Keys will be discarded.
     * 
     * @param callable $callback
     * @return \Framework\IEnumerable
     */
    public function selectMany(callable $callback): IEnumerable;

    /**
     * Returns an array copy of this enumerable.
     * 
     * @return array
     */
    public function toArray(): array;

    /**
     * Gets the union of this enumerable with the other collection. The duplicate values from the other collection will be omitted.
     * The keys from the other collection will not be used.
     * 
     * @param mixed $otherCollection Any traversable object (array, IEnumerable, \Traversable)
     * @param callable $comparison An optional comparison function, it will receive an object from this enumerable and one from the other one. It should return true if the objects are equals, false otherwise. By default, this method will use '==' operator.
     * @return \Framework\IEnumerable
     */
    public function union($otherCollection, callable $comparison = null): IEnumerable;

    /**
     * Appends the given collection to this enumerable. The other collection keys will be ignored, this collection keys will be kept.
     * 
     * @param mixed $otherCollection
     * @return \Framework\IEnumerable
     */
    public function concat($otherCollection): IEnumerable;

    /**
     * Extends this enumerable by adding the values from the other collection by key. Any value from the other collection with the same key as this enumerable, will override this enumerable's value (analog to jQuery's $.merge function).
     * 
     * @param mixed $otherCollection
     * @return \Framework\IEnumerable
     */
    public function extend($otherCollection): IEnumerable;

    /**
     * Checks if any element in this collection fulfills the given predicate.
     * 
     * @param callable $predicate A function that will receive the value and, optionally, the key of an element in this collection, and that should return true if the given element fulfills the condition. If null, it will just check if there's at least one element in this enumerable.
     * @return bool True if at least one of the elements of this enumerable complies with the given predicate. False otherwise.
     */
    public function any(callable $predicate = null): bool;

    /**
     * Checks if all elements in this collection fulfill the given predicate.
     * 
     * @param callable $predicate A function that will receive the value and, optionally, the key of an element in this collection, and that should return true if the given element fulfills the condition.
     * @return bool True if all of the elements in this enumerable comply with the given predicate. False otherwise.
     */
    public function all(callable $predicate): bool;

    /**
     * Returns the first value that fulfills the given predicate. If no predicate is given, it will return the first element in the collection.
     * 
     * @param callable $predicate The condition that a value must meet to be returned.
     * @throws Exception If no value fulfills the given condition or the collection is empty.
     */
    public function first(callable $predicate = null);
    
    /**
     * Returns the last value that fulfills the given predicate. If no predicate is given, it will return the last element in the collection.
     * 
     * @param callable $predicate The condition that a value must meet to be returned.
     * @throws Exception If no value fulfills the given condition or the collection is empty.
     */
    public function last(callable $predicate = null);
    
    /**
     * Returns the first value that fulfills the given predicate or the given default value if none comply with the condition. If no predicate is given, the first value in the collection will be returned. If the collection is empty, the given default value is returned.
     * 
     * @param mixed $default The default value that will be returned if no other value complies with the condition
     * @param callable $predicate The condition that a value must meet to be returned.
     */
    public function firstOrDefault($default = null, callable $predicate = null);
    
    /**
     * Returns the last value that fulfills the given predicate or the given default value if none comply with the condition. If no predicate is given, the last value in the collection will be returned. If the collection is empty, the given default value is returned.
     * 
     * @param mixed $default The default value that will be returned if no other value complies with the condition
     * @param callable $predicate The condition that a value must meet to be returned.
     */
    public function lastOrDefault($default = null, callable $predicate = null);
    
    /**
     * Returns a collection with the first $count elements in this enumerable.
     * 
     * @param int $count The number of elements to be taken.
     * @return \Framework\IEnumerable The first $count elements in this collection.
     */
    public function take(int $count) : IEnumerable;

    /**
     * Returns a collection skipping the first $count elements in this enumerable. If the number of elements to be skipped is higher than the count of this enumerable, an empty enumerable will be returned.
     * 
     * @param int $count
     * @return IEnumerable The elements from this enumerable after skipping the first $count elements. 
     */
    public function skip(int $count) : IEnumerable;
    
    /**
     * Calls the given callable for each element in this enumerable. 
     * 
     * @param callable $callback A callable that will receive the current value as the first parameter and the key as the second parameter.
     * @return void
     */
    public function each(callable $callback): void;

    // public function countIf();
    // public function takeWhile();
    // public function skipWhile();
    // public function sum();
    // public function average();
    // public function aggregate();
}
