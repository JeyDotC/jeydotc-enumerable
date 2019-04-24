<?php

use JeyDotC\EnumerableSet;
use JeyDotC\IEnumerable;
use JeyDotC\ISet;
use PHPUnit\Framework\TestCase;

// PhpUnit has issues with the autoloader for traits, so, decided to just include it.
require_once __DIR__ . '/Contracts/IEnumerableContractTests.php';
require_once __DIR__ . '/Contracts/ISetContractTests.php';

/**
 * Description of EnumerableTest
 *
 * @author jguevara
 */
class EnumerableSetTest extends TestCase
{

    use ISetContractTests;

    protected function setFrom(array $data): ISet {
        return EnumerableSet::from($data);
    }


    protected function enumerableFrom(array $data): IEnumerable {
        return $this->setFrom($data);
    }

    public function testEmpty() {
        // Act
        $enumerable = EnumerableSet::empty();

        // Assert
        $this->assertInstanceOf(IEnumerable::class, $enumerable);
        $this->assertInstanceOf(ISet::class, $enumerable);
        $this->assertEquals(0, $enumerable->count());
        $this->assertFalse($enumerable->any());
    }

    public function testFromWithSimpleData() {
        // Act
        $enumerable = EnumerableSet::from(self::$basicSampleData);

        // Assert
        $this->assertInstanceOf(IEnumerable::class, $enumerable);
        $this->assertInstanceOf(ISet::class, $enumerable);

        $this->assertEquals(self::$basicSampleData, $enumerable->toArray());
    }

    public function testFromWithSimpleDataWithKeys() {
        // Act
        $enumerable = EnumerableSet::from(self::$basicSampleDataWithKeys);

        // Assert
        $this->assertInstanceOf(IEnumerable::class, $enumerable);
        $this->assertInstanceOf(ISet::class, $enumerable);

        $this->assertEquals(self::$basicSampleDataWithKeys, $enumerable->toArray());
    }

    public function testFromWithAnotherEnumerable() {
        // Act
        $enumerable1 = EnumerableSet::from(self::$basicSampleData);
        $enumerable2 = EnumerableSet::from($enumerable1);

        // Assert
        $this->assertInstanceOf(IEnumerable::class, $enumerable1);
        $this->assertInstanceOf(ISet::class, $enumerable1);

        $this->assertInstanceOf(IEnumerable::class, $enumerable2);
        $this->assertInstanceOf(ISet::class, $enumerable2);

        $this->assertEquals($enumerable1, $enumerable2);
    }

}
