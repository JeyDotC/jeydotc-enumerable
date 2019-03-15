<?php

use JeyDotC\IList;

/**
 *
 * @author jguevara
 */
trait IListContractTests
{

    use IEnumerableContractTests;

    protected abstract function listFrom(array $data): IList;

    /**
     * @dataProvider addDataProvider
     * 
     * @param IList $enumerableList
     * @param type $valueToAdd
     * @param array $expectedValues
     */
    public function testAdd(IList $enumerableList, $valueToAdd, array $expectedValues) {
        // Act
        $enumerableList->add($valueToAdd);

        // Assert
        $this->assertEquals($expectedValues, $enumerableList->toArray());
    }

    /**
     * @dataProvider offsetExistsProvider
     * 
     * @param IList $enumerableList
     * @param type $offset
     * @param bool $shouldExist
     */
    public function testOffsetExists(IList $enumerableList, $offset, bool $shouldExist) {
        // Act
        $result = $enumerableList->offsetExists($offset);
        $specialSyntaxResult = isset($enumerableList[$offset]);

        // Assert
        $this->assertEquals($result, $specialSyntaxResult);
        $this->assertEquals($shouldExist, $result);
        $this->assertEquals($shouldExist, $specialSyntaxResult);
    }

    /**
     * @dataProvider offsetGetProvider
     * 
     * @param IList $enumerableList
     * @param type $offset
     * @param type $expectedValue
     */
    public function testOffsetGet(IList $enumerableList, $offset, $expectedValue) {
        // Act
        $result = $enumerableList->offsetGet($offset);
        $specialSyntaxResult = $enumerableList[$offset];

        // Assert
        $this->assertEquals($result, $specialSyntaxResult);
        $this->assertEquals($expectedValue, $result);
        $this->assertEquals($expectedValue, $specialSyntaxResult);
    }

    /**
     * @dataProvider offsetSetProvider
     * 
     * @param IList $enumerableList
     * @param type $offset
     * @param type $value
     * @param array $expectedResult
     */
    public function testOffsetSet(IList $enumerableList, $offset, $value, array $expectedResult) {
        // Arrange
        $listCopyForSpecialSyntax = $this->listFrom($enumerableList->toArray());

        // Act
        $enumerableList->offsetSet($offset, $value);
        $listCopyForSpecialSyntax[$offset] = $value;

        // Assert
        $result = $enumerableList->toArray();
        $specialSyntaxResult = $listCopyForSpecialSyntax->toArray();

        $this->assertEquals($result, $specialSyntaxResult);
        $this->assertEquals($expectedResult, $result);
        $this->assertEquals($expectedResult, $specialSyntaxResult);
    }

    /**
     * @dataProvider offsetUnsetProvider
     * 
     * @param IList $enumerableList
     * @param type $offset
     * @param type $value
     * @param array $expectedResult
     */
    public function testOffsetUnset(IList $enumerableList, $offset, array $expectedResult) {
        // Arrange
        $listCopyForSpecialSyntax = $this->listFrom($enumerableList->toArray());

        // Act
        $enumerableList->offsetUnset($offset);
        unset($listCopyForSpecialSyntax[$offset]);

        // Assert
        $result = $enumerableList->toArray();
        $specialSyntaxResult = $listCopyForSpecialSyntax->toArray();

        $this->assertEquals($result, $specialSyntaxResult);
        $this->assertEquals($expectedResult, $result);
        $this->assertEquals($expectedResult, $specialSyntaxResult);
    }

    /**
     * @dataProvider clearProvider
     * 
     * @param IList $enumerableList
     */
    public function testClear(IList $enumerableList) {
        // Act
        $enumerableList->clear();

        // Assert
        $this->assertEquals([], $enumerableList->toArray());
        $this->assertEquals(0, $enumerableList->count());
        $this->assertFalse($enumerableList->any());
    }

    public function addDataProvider() {
        return [
            'Add simple data' => [
                $this->listFrom(self::$basicSampleData),
                'D',
                ['A', 'B', 'C', 'D']
            ]
        ];
    }

    function offsetExistsProvider() {
        return [
            'Existing numeric index' => [
                $this->listFrom(self::$basicSampleData),
                0,
                true
            ],
            'Existing numeric index 2' => [
                $this->listFrom(self::$basicSampleData),
                2,
                true
            ],
            'Not existing numeric index' => [
                $this->listFrom(self::$basicSampleData),
                3,
                false
            ],
            'Existing string index' => [
                $this->listFrom(self::$basicSampleDataWithKeys),
                'One',
                true
            ],
            'Existing string index Three' => [
                $this->listFrom(self::$basicSampleDataWithKeys),
                'Three',
                true
            ],
            'Not existing string index' => [
                $this->listFrom(self::$basicSampleDataWithKeys),
                'Nine',
                false
            ],
        ];
    }

    function offsetGetProvider() {
        return [
            'Existing numeric index' => [
                $this->listFrom(self::$basicSampleData),
                0,
                'A'
            ],
            'Existing numeric index 2' => [
                $this->listFrom(self::$basicSampleData),
                2,
                'C'
            ],
            'Existing string index' => [
                $this->listFrom(self::$basicSampleDataWithKeys),
                'One',
                'A'
            ],
            'Existing string index Three' => [
                $this->listFrom(self::$basicSampleDataWithKeys),
                'Three',
                'C'
            ],
        ];
    }

    function offsetSetProvider() {
        return [
            'Add key' => [
                $this->listFrom(self::$basicSampleData),
                'Key',
                'D',
                ['A', 'B', 'C', 'Key' => 'D']
            ],
            'Append to the end' => [
                $this->listFrom(self::$basicSampleData),
                null,
                'D',
                ['A', 'B', 'C', 'D']
            ],
            'Replace' => [
                $this->listFrom(self::$basicSampleData),
                1,
                'D',
                ['A', 'D', 'C']
            ]
        ];
    }

    function offsetUnsetProvider() {
        return [
            'Add key' => [
                $this->listFrom(self::$basicSampleData),
                1,
                ['A', 2 => 'C']
            ],
            'Append to the end' => [
                $this->listFrom(self::$basicSampleDataWithKeys),
                'Two',
                ['One' => 'A', 'Three' => 'C']
            ]
        ];
    }

    function clearProvider() {
        return [
          'Basic' => [
              $this->listFrom(self::$basicSampleData)
          ],
          'Basic wih keys' => [
              $this->listFrom(self::$basicSampleDataWithKeys)
          ],
        ];
    }

}
