# Enumerable class!

```php
$myEnumerable = Enumerable::from([ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

$even = $myEnumerable->where(function(int $number){
    return $number % 2 === 0;
});
// new Enumerable: 2, 4, 6, 8, 10

$allPowered = $myEnumerable->select(function(int $number){
    return $number * $number;
});
// new Enumerable:  1, 4, 9, 16, 25, 36, 48, 64, 81, 100

$theAlpha = $myEnumerable->first();
// 1

$theOmega = $myEnumerable->last();
// 10

// And many more...
```
