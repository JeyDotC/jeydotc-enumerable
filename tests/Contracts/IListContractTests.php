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

    /*  public function testOffsetExists(IList $enumerableList): bool {

      }

      public function testOffsetGet(IList $enumerableList) {

      }

      public function testOffsetSet(IList $enumerableList): void {

      }

      public function testOffsetUnset(IList $enumerableList): void {

      }

      public function testClear(IList $enumerableList) {

      } */

    public function addDataProvider() {
        return [
            'Add simple data' => [
                $this->listFrom(self::$basicSampleData),
                'D',
                ['A', 'B', 'C', 'D']
            ]
        ];
    }

}
