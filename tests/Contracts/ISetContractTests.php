<?php

use JeyDotC\ISet;

/**
 *
 * @author jguevara
 */
trait ISetContractTests
{

    use IEnumerableContractTests;

    protected abstract function setFrom(array $data): Iset;

    /**
     * @dataProvider addDataProvider
     * 
     * @param ISet $enumerableList
     * @param type $valueToAdd
     * @param array $expectedValues
     */
    public function testAdd(ISet $enumerableList, $valueToAdd, array $expectedValues) {
        // Act
        $enumerableList->add($valueToAdd);

        // Assert
        $this->assertEquals($expectedValues, $enumerableList->toArray());
    }

    
    /**
     * @dataProvider clearProvider
     * 
     * @param ISet $enumerableList
     */
    public function testClear(ISet $enumerableList) {
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
                $this->setFrom(self::$basicSampleData),
                'D',
                ['A', 'B', 'C', 'D']
            ],
            'Add repeated data' => [
                $this->setFrom(self::$basicSampleData),
                'A',
                ['A', 'B', 'C']
            ],
            'Add repeated data 2' => [
                $this->setFrom(self::$basicSampleData),
                'B',
                ['A', 'B', 'C']
            ],
        ];
    }

    function clearProvider() {
        return [
          'Basic' => [
              $this->setFrom(self::$basicSampleData)
          ],
          'Basic wih keys' => [
              $this->setFrom(self::$basicSampleDataWithKeys)
          ],
        ];
    }

}
