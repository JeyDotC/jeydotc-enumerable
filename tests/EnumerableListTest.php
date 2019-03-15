<?php

use JeyDotC\EnumerableList;
use JeyDotC\IEnumerable;
use JeyDotC\IList;
use PHPUnit\Framework\TestCase;

// PhpUnit has issues with the autoloader for traits, so, decided to just include it.
require_once __DIR__ . '/Contracts/IEnumerableContractTests.php';
require_once __DIR__ . '/Contracts/IListContractTests.php';

/**
 * Description of EnumerableTest
 *
 * @author jguevara
 */
class EnumerableListTest extends TestCase
{

    use IListContractTests;

    protected function listFrom(array $data): IList {
        return EnumerableList::from($data);
    }

    protected function enumerableFrom(array $data): IEnumerable {
        return $this->listFrom($data);
    }

    public function testEmpty() {
        // Act
        $enumerable = EnumerableList::empty();

        // Assert
        $this->assertInstanceOf(IEnumerable::class, $enumerable);
        $this->assertInstanceOf(IList::class, $enumerable);
        $this->assertEquals(0, $enumerable->count());
        $this->assertFalse($enumerable->any());
    }

    public function testFromWithSimpleData() {
        // Act
        $enumerable = EnumerableList::from(self::$basicSampleData);

        // Assert
        $this->assertInstanceOf(IEnumerable::class, $enumerable);
        $this->assertInstanceOf(IList::class, $enumerable);

        $this->assertEquals(self::$basicSampleData, $enumerable->toArray());
    }

    public function testFromWithSimpleDataWithKeys() {
        // Act
        $enumerable = EnumerableList::from(self::$basicSampleDataWithKeys);

        // Assert
        $this->assertInstanceOf(IEnumerable::class, $enumerable);
        $this->assertInstanceOf(IList::class, $enumerable);

        $this->assertEquals(self::$basicSampleDataWithKeys, $enumerable->toArray());
    }

    public function testFromWithAnotherEnumerable() {
        // Act
        $enumerable1 = EnumerableList::from(self::$basicSampleData);
        $enumerable2 = EnumerableList::from($enumerable1);

        // Assert
        $this->assertInstanceOf(IEnumerable::class, $enumerable1);
        $this->assertInstanceOf(IList::class, $enumerable1);

        $this->assertInstanceOf(IEnumerable::class, $enumerable2);
        $this->assertInstanceOf(IList::class, $enumerable2);

        $this->assertEquals($enumerable1, $enumerable2);
    }

}
