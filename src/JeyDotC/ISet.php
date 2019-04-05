<?php

namespace JeyDotC;

/**
 * An enumerable which values must be unique.
 * 
 * @author jguevara
 */
interface ISet extends IEnumerable
{
    public function add($value);
    
    public function clear();
}
