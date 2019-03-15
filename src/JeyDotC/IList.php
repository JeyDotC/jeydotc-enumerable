<?php

namespace JeyDotC;

use ArrayAccess;

/**
 * A collection that can be modified by adding or changing elements.
 * 
 * Since it implements the ArrayAccess interface, it can be treated 
 * as an array: $myList[] = $someValue; $myList['Key'] = $someValue;
 * 
 * @author jguevara
 */
interface IList extends IEnumerable, ArrayAccess
{
    /**
     * Appends a value to this list.
     * 
     * @param mixed $value
     */
    public function add($value);
    
    /**
     * Leaves this list empty.
     */
    public function clear();
}
