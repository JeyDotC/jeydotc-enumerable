<?php

use JeyDotC\Enumerable;
use JeyDotC\IEnumerable;
use PHPUnit\Framework\TestCase;

// PhpUnit has issues with the autoloader for traits, so, decided to just include it.
require_once __DIR__ . '/Contracts/IEnumerableContractTests.php';

/**
 * Description of EnumerableTest
 *
 * @author jguevara
 */
class EnumerableTest extends TestCase
{
    use IEnumerableContractTests;

    protected function enumerableFrom(array $data): IEnumerable {
        return Enumerable::from($data);
    }
    
    public function testEmpty() {
        // Act
        $enumerable = Enumerable::empty();
        
        // Assert
        $this->assertInstanceOf(IEnumerable::class, $enumerable);
        $this->assertEquals(0, $enumerable->count());
        $this->assertFalse($enumerable->any());
    }

    public function testFromWithSimpleData() {
        // Act
        $enumerable = Enumerable::from(self::$basicSampleData);

        // Assert
        $this->assertInstanceOf(IEnumerable::class, $enumerable);
        $this->assertEquals(self::$basicSampleData, $enumerable->toArray());
    }

    public function testFromWithSimpleDataWithKeys() {
        // Act
        $enumerable = Enumerable::from(self::$basicSampleDataWithKeys);

        // Assert
        $this->assertInstanceOf(IEnumerable::class, $enumerable);
        $this->assertEquals(self::$basicSampleDataWithKeys, $enumerable->toArray());
    }

    public function testFromWithAnotherEnumerable() {
        // Act
        $enumerable1 = Enumerable::from(self::$basicSampleData);
        $enumerable2 = Enumerable::from($enumerable1);

        // Assert
        $this->assertInstanceOf(IEnumerable::class, $enumerable1);
        $this->assertInstanceOf(IEnumerable::class, $enumerable2);
        $this->assertEquals($enumerable1, $enumerable2);
    }
}