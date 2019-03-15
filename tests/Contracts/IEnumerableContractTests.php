<?php

use JeyDotC\IEnumerable;

class TestFoo
{

    var $foo;
    var $bar;

    public function __construct($foo = null, $bar = null) {
        $this->foo = $foo;
        $this->bar = $bar;
    }

}

class TestBar extends TestFoo
{
    
}

/**
 *
 * @author jguevara
 */
trait IEnumerableContractTests
{

    // <editor-fold defaultstate="collapsed" desc="Test data">

    protected static $basicSampleData = [
        'A',
        'B',
        'C'
    ];
    protected static $basicSampleDataWithKeys = [
        'One' => 'A',
        'Two' => 'B',
        'Three' => 'C'
    ];

    protected static function basicObjectData(): array {
        return [
            new TestFoo('A', 'Bar1'),
            new TestFoo('B', 'Bar2'),
            new TestFoo('C', 'Bar3'),
            new TestFoo('AA', 'Bar1'),
            new TestFoo('BB', 'Bar2'),
            new TestFoo('CC', 'Bar3'),
        ];
    }

    protected static function basicObjectDataWithKeys(): array {
        return [
            'One' => new TestFoo('A', 'Bar1'),
            'Two' => new TestFoo('B', 'Bar2'),
            'Three' => new TestFoo('C', 'Bar3'),
            'OneOne' => new TestFoo('AA', 'Bar1'),
            'TwoTwo' => new TestFoo('BB', 'Bar2'),
            'ThreeThree' => new TestFoo('CC', 'Bar3'),
        ];
    }

    protected static function mixedObjectData(): array {
        return [
            'A',
            new TestFoo('A', 'Bar1'),
            'B',
            new TestFoo('B', 'Bar2'),
            'C',
            new TestFoo('C', 'Bar3'),
            new stdClass(),
            40,
            new TestBar('X', 'Bar4'),
            5
        ];
    }

    // </editor-fold>

    protected abstract function enumerableFrom(array $data): IEnumerable;

    /**
     * @dataProvider whereDataProvider
     * 
     * @param IEnumerable $enumerable
     * @param callable $condition
     * @param array $expectedContents
     */
    public function testWhere(IEnumerable $enumerable, callable $condition, array $expectedContents) {

        // Arrange
        $originalContents = $enumerable->toArray();

        // Act
        $actualResult = $enumerable->where($condition);

        // Assert
        $actualContents = $actualResult->toArray();
        $this->assertInstanceOf(IEnumerable::class, $actualResult);
        $this->assertEquals($expectedContents, $actualContents);
        // Check that the original object is not modified.
        $this->assertEquals($originalContents, $enumerable->toArray());
    }

    /**
     * @dataProvider selectDataProvider
     * 
     * @param IEnumerable $enumerable
     * @param callable $select
     * @param array $expectedContents
     */
    public function testSelect(IEnumerable $enumerable, callable $select, array $expectedContents) {
        // Arrange
        $originalContents = $enumerable->toArray();

        // Act
        $actualResult = $enumerable->select($select);

        // Assert
        $actualContents = $actualResult->toArray();
        $this->assertInstanceOf(IEnumerable::class, $actualResult);
        $this->assertEquals($expectedContents, $actualContents);
        $this->assertEquals($originalContents, $enumerable->toArray());
    }

    /**
     * @dataProvider keysDataProvider
     * @param IEnumerable $enumerable
     * @param array $expectedKeys
     */
    public function testKeys(IEnumerable $enumerable, array $expectedKeys) {
        // Arrange
        $originalContents = $enumerable->toArray();

        // Act
        $actualKeys = $enumerable->keys();

        // Assert
        $this->assertInstanceOf(IEnumerable::class, $actualKeys);
        $this->assertEquals($expectedKeys, $actualKeys->toArray());
        $this->assertEquals($originalContents, $enumerable->toArray());
    }

    /**
     * @dataProvider valuesDataProvider
     * @param IEnumerable $enumerable
     * @param array $expectedValues
     */
    public function testValues(IEnumerable $enumerable, array $expectedValues) {
        // Arrange
        $originalContents = $enumerable->toArray();

        // Act
        $actualValues = $enumerable->values();

        // Assert
        $this->assertInstanceOf(IEnumerable::class, $actualValues);
        $this->assertEquals($expectedValues, $actualValues->toArray());
        $this->assertEquals($originalContents, $enumerable->toArray());
    }

    /**
     * @dataProvider groupByDataProvider
     * @param IEnumerable $enumerable
     * @param callable $discriminator
     * @param array $expectedValues
     */
    public function testGroupBy(IEnumerable $enumerable, callable $discriminator, array $expectedValues) {
        // Arrange
        $originalContents = $enumerable->toArray();

        // Act
        $result = $enumerable->groupBy($discriminator);

        // Assert
        $this->assertInstanceOf(IEnumerable::class, $result);
        $this->assertEquals($expectedValues, $result->toArray());
        $this->assertEquals($originalContents, $enumerable->toArray());
    }

    public function testOrderBy() {
        // Arrange
        $enumerable = $this->enumerableFrom(self::basicObjectData());
        $orderCallback = function(TestFoo $foo1, TestFoo $foo2) {
            $value1 = $foo1->foo[0];
            $value2 = $foo2->foo[0];

            if ($value1 == $value2) {
                return 0;
            }

            if ($value1 > $value2) {
                return 1;
            }

            return -1;
        };

        $expectedValues = [
            0 => new TestFoo('A', 'Bar1'),
            3 => new TestFoo('AA', 'Bar1'),
            1 => new TestFoo('B', 'Bar2'),
            4 => new TestFoo('BB', 'Bar2'),
            2 => new TestFoo('C', 'Bar3'),
            5 => new TestFoo('CC', 'Bar3'),
        ];
        $originalContents = $enumerable->toArray();

        // Act
        $result = $enumerable->orderBy($orderCallback);

        // Assert
        $this->assertInstanceOf(IEnumerable::class, $result);
        $this->assertEquals($expectedValues, $result->toArray());
        $this->assertEquals($originalContents, $enumerable->toArray());
    }

    public function testOrderByDesc() {
        // Arrange
        $enumerable = $this->enumerableFrom(self::basicObjectData());
        $orderCallback = function(TestFoo $foo1, TestFoo $foo2) {
            $value1 = $foo1->foo[0];
            $value2 = $foo2->foo[0];

            if ($value1 == $value2) {
                return 0;
            }

            if ($value1 > $value2) {
                return 1;
            }

            return -1;
        };

        $expectedValues = [
            5 => new TestFoo('CC', 'Bar3'),
            2 => new TestFoo('C', 'Bar3'),
            4 => new TestFoo('BB', 'Bar2'),
            1 => new TestFoo('B', 'Bar2'),
            3 => new TestFoo('AA', 'Bar1'),
            0 => new TestFoo('A', 'Bar1'),
        ];
        $originalContents = $enumerable->toArray();

        // Act
        $result = $enumerable->orderBy($orderCallback, 'desc');

        // Assert
        $this->assertInstanceOf(IEnumerable::class, $result);
        $this->assertEquals($expectedValues, $result->toArray());
        $this->assertEquals($originalContents, $enumerable->toArray());
    }

    public function testOfType() {
        // Arrange
        $enumerable = $this->enumerableFrom(self::mixedObjectData());

        $expectedValues = [
            1 => new TestFoo('A', 'Bar1'),
            3 => new TestFoo('B', 'Bar2'),
            5 => new TestFoo('C', 'Bar3'),
            8 => new TestBar('X', 'Bar4'),
        ];

        // Act
        $result = $enumerable->ofType(TestFoo::class);

        // Assert
        $this->assertInstanceOf(IEnumerable::class, $result);
        $this->assertEquals($expectedValues, $result->toArray());
    }

    public function testSelectMany() {
        // Arrange
        $enumerable = $this->enumerableFrom(self::basicObjectData());

        $callback = function(TestFoo $foo, $key) {
            return [$key, $foo->foo, $foo->bar];
        };

        $expectedResult = [
            0, 'A', 'Bar1', 1, 'B', 'Bar2', 2, 'C', 'Bar3', 3, 'AA', 'Bar1', 4, 'BB', 'Bar2', 5, 'CC', 'Bar3',
        ];

        // Act
        $actualResult = $enumerable->selectMany($callback);

        // Assert
        $this->assertInstanceOf(IEnumerable::class, $actualResult);
        $this->assertEquals($expectedResult, $actualResult->toArray());
    }

    public function testAnyWithNoPredicate() {
        // Arrange
        $enumerable = $this->enumerableFrom(self::mixedObjectData());
        $emptyEnumerable = $this->enumerableFrom([]);

        // Act
        $notEmptyResult = $enumerable->any();
        $emptyResult = $emptyEnumerable->any();

        // Assert
        $this->assertTrue($notEmptyResult === true);
        $this->assertTrue($emptyResult === false);
    }

    public function testAnyWithPredicate() {
        // Arrange
        $enumerable = $this->enumerableFrom(self::basicObjectData());
        $comparisonTrue = function(TestFoo $value) {
            return $value->foo == 'A';
        };
        $comparisonFalse = function(TestFoo $value) {
            return $value->foo == 'NoOne';
        };

        // Act
        $trueResult = $enumerable->any($comparisonTrue);
        $falseResult = $enumerable->any($comparisonFalse);

        // Assert
        $this->assertTrue($trueResult === true);
        $this->assertTrue($falseResult === false);
    }

    public function testAnyWithPredicateAndKeys() {
        // Arrange
        $enumerable = $this->enumerableFrom(self::basicObjectDataWithKeys());
        $comparisonTrue = function(TestFoo $value, $key) {
            return $value->foo == 'A' && $key == 'One';
        };
        $comparisonFalse = function(TestFoo $value, $key) {
            return $value->foo == 'NoOne' || $key == 'Nothing';
        };

        // Act
        $trueResult = $enumerable->any($comparisonTrue);
        $falseResult = $enumerable->any($comparisonFalse);

        // Assert
        $this->assertTrue($trueResult === true);
        $this->assertTrue($falseResult === false);
    }

    /**
     * @dataProvider unionDataProvider
     * 
     * @param IEnumerable $original
     * @param type $collectionToJoin
     * @param type $expectedResult
     */
    public function testUnion(IEnumerable $original, $otherCollection, array $expectedResult, callable $comparison = null) {

        // Act
        $actualResult = $original->union($otherCollection, $comparison);

        // Assert
        $this->assertInstanceOf(IEnumerable::class, $actualResult);
        $this->assertEquals($expectedResult, $actualResult->toArray());
    }

    /**
     * @dataProvider allDataProvider
     * 
     * @param IEnumerable $enumerable
     * @param callable $condition
     * @param bool $expectedresult
     */
    public function testAll(IEnumerable $enumerable, callable $condition, bool $expectedresult) {

        // Act
        $actualResult = $enumerable->all($condition);

        // Assert
        $this->assertEquals($expectedresult, $actualResult);
    }

    /**
     * @dataProvider extendDataProvider
     * 
     * @param IEnumerable $enumerable
     * @param mixed $otherCollection
     * @param IEnumerable $expectedresult
     */
    public function testExtend(IEnumerable $enumerable, $otherCollection, array $expectedresult) {

        // Act
        $actualResult = $enumerable->extend($otherCollection);

        // Assert
        $this->assertInstanceOf(IEnumerable::class, $actualResult);
        $this->assertEquals($expectedresult, $actualResult->toArray());
    }

    public function testFirstNoCondition() {
        // Arrange
        $enumerable = $this->enumerableFrom(self::$basicSampleData);

        // Act
        $actualResult = $enumerable->first();

        // Assert
        $this->assertEquals('A', $actualResult);
    }

    public function testLastNoCondition() {
        // Arrange
        $enumerable = $this->enumerableFrom(self::$basicSampleData);

        // Act
        $actualResult = $enumerable->last();

        // Assert
        $this->assertEquals('C', $actualResult);
    }

    /**
     * 
     * @dataProvider firstDataProvider
     * 
     * @param IEnumerable $enumerable
     * @param callable $condition
     * @param type $expectedResult
     */
    public function testFirst(IEnumerable $enumerable, callable $condition, $expectedResult) {
        // Act
        $actualResult = $enumerable->first($condition);

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * 
     * @dataProvider lastDataProvider
     * 
     * @param IEnumerable $enumerable
     * @param callable $condition
     * @param type $expectedResult
     */
    public function testLast(IEnumerable $enumerable, callable $condition, $expectedResult) {
        // Act
        $actualResult = $enumerable->last($condition);

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * 
     * @dataProvider firstAndLastWithErrorsDataProvider
     * 
     * @param IEnumerable $enumerable
     * @param callable $condition
     */
    public function testFirstErrors(IEnumerable $enumerable, callable $condition) {
        // Assert
        $this->expectException(Exception::class);

        // Act
        $enumerable->first($condition);
    }

    /**
     * 
     * @dataProvider firstAndLastWithErrorsDataProvider
     * 
     * @param IEnumerable $enumerable
     * @param callable $condition
     */
    public function testLastErrors(IEnumerable $enumerable, callable $condition) {
        // Assert
        $this->expectException(Exception::class);

        // Act
        $enumerable->last($condition);
    }

    public function testFirstOrDefaultNoCondition() {
        // Arrange
        $enumerable = $this->enumerableFrom(self::$basicSampleData);

        // Act
        $actualResult1 = $enumerable->firstOrDefault();
        $actualResult2 = $enumerable->firstOrDefault('Default');

        // Assert
        $this->assertEquals('A', $actualResult1);
        $this->assertEquals('A', $actualResult2);
    }

    /**
     * 
     * @dataProvider firstOrDefaultDataProvider
     * 
     * @param IEnumerable $enumerable
     * @param callable $condition
     * @param type $expectedResult
     */
    public function testFirstOrDefault(IEnumerable $enumerable, callable $condition, $default, $expectedResult) {
        // Act
        $actualResult = $enumerable->firstOrDefault($default, $condition);

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testLastOrDefaultNoCondition() {
        // Arrange
        $enumerable = $this->enumerableFrom(self::$basicSampleData);

        // Act
        $actualResult1 = $enumerable->lastOrDefault();
        $actualResult2 = $enumerable->lastOrDefault('Default');

        // Assert
        $this->assertEquals('C', $actualResult1);
        $this->assertEquals('C', $actualResult2);
    }

    /**
     * 
     * @dataProvider lastOrDefaultDataProvider
     * 
     * @param IEnumerable $enumerable
     * @param callable $condition
     * @param type $expectedResult
     */
    public function testLastOrDefault(IEnumerable $enumerable, callable $condition, $default, $expectedResult) {
        // Act
        $actualResult = $enumerable->lastOrDefault($default, $condition);

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * 
     * @dataProvider takeDataProvider
     * 
     * @param IEnumerable $enumerable
     * @param int $count
     * @param type $expectedResult
     */
    public function testTake(IEnumerable $enumerable, int $count, $expectedResult) {
        // Act
        $actualResult = $enumerable->take($count);

        // Assert
        $this->assertEquals($expectedResult, $actualResult->toArray());
    }

    /**
     * 
     * @dataProvider skipDataProvider
     * 
     * @param IEnumerable $enumerable
     * @param int $count
     * @param type $expectedResult
     */
    public function testSkip(IEnumerable $enumerable, int $count, $expectedResult) {
        // Act
        $actualResult = $enumerable->skip($count);

        // Assert
        $this->assertEquals($expectedResult, $actualResult->toArray());
    }

    // <editor-fold defaultstate="expanded" desc="Data Providers">

    public function skipDataProvider() {
        return [
            'BasicCase' => [
                $this->enumerableFrom(self::$basicSampleData),
                2,
                [2 => 'C'],
            ],
            'BasicCaseWithObjects' => [
                $this->enumerableFrom(self::basicObjectData()),
                3,
                [
                    3 => new TestFoo('AA', 'Bar1'),
                    4 => new TestFoo('BB', 'Bar2'),
                    5 => new TestFoo('CC', 'Bar3'),
                ],
            ],
            'BasicCaseWithObjectsAndKeys' => [
                $this->enumerableFrom(self::basicObjectDataWithKeys()),
                3,
                [
                    'OneOne' => new TestFoo('AA', 'Bar1'),
                    'TwoTwo' => new TestFoo('BB', 'Bar2'),
                    'ThreeThree' => new TestFoo('CC', 'Bar3'),
                ],
            ],
            'OutOfOffset' => [
                $this->enumerableFrom(self::basicObjectDataWithKeys()),
                count(self::basicObjectDataWithKeys()) + 3,
                [],
            ],
            'EmptyCollection' => [
                $this->enumerableFrom([]),
                3,
                []
            ]
        ];
    }

    public function takeDataProvider() {
        return [
            'BasicCase' => [
                $this->enumerableFrom(self::$basicSampleData),
                2,
                ['A', 'B'],
            ],
            'BasicCaseWithObjects' => [
                $this->enumerableFrom(self::basicObjectData()),
                3,
                [
                    new TestFoo('A', 'Bar1'),
                    new TestFoo('B', 'Bar2'),
                    new TestFoo('C', 'Bar3'),
                ],
            ],
            'BasicCaseWithObjectsAndKeys' => [
                $this->enumerableFrom(self::basicObjectDataWithKeys()),
                3,
                [
                    'One' => new TestFoo('A', 'Bar1'),
                    'Two' => new TestFoo('B', 'Bar2'),
                    'Three' => new TestFoo('C', 'Bar3'),
                ],
            ],
            'OutOfOffset' => [
                $this->enumerableFrom(self::basicObjectDataWithKeys()),
                count(self::basicObjectDataWithKeys()) + 3,
                self::basicObjectDataWithKeys(),
            ],
            'EmptyCollection' => [
                $this->enumerableFrom([]),
                3,
                []
            ]
        ];
    }

    public function lastOrDefaultDataProvider() {
        return [
            'EmptyCollection' => [
                $this->enumerableFrom([]),
                function() {
                    
                },
                'Default',
                'Default'
            ],
            'NotMetCondition' => [
                $this->enumerableFrom(self::$basicSampleData),
                function($data) {
                    return $data == 'NOT VALID VALUE';
                },
                'Default',
                'Default'
            ],
            'BasicCondition' => [
                $this->enumerableFrom(self::$basicSampleData),
                function($data) {
                    return $data == 'B';
                },
                'Default',
                'B'
            ],
            'WithKeys' => [
                $this->enumerableFrom(self::$basicSampleDataWithKeys),
                function($data, $key) {
                    return $key == 'Two';
                },
                'Default',
                'B'
            ],
            'MultipleSuitableCandidates' => [
                $this->enumerableFrom(self::basicObjectData()),
                function(TestFoo $data) {
                    return $data->bar == 'Bar2';
                },
                'Default',
                new TestFoo('BB', 'Bar2')
            ]
        ];
    }

    public function firstOrDefaultDataProvider() {
        return [
            'EmptyCollection' => [
                $this->enumerableFrom([]),
                function() {
                    
                },
                'Default',
                'Default'
            ],
            'NotMetCondition' => [
                $this->enumerableFrom(self::$basicSampleData),
                function($data) {
                    return $data == 'NOT VALID VALUE';
                },
                'Default',
                'Default'
            ],
            'BasicCondition' => [
                $this->enumerableFrom(self::$basicSampleData),
                function($data) {
                    return $data == 'B';
                },
                'Default',
                'B'
            ],
            'WithKeys' => [
                $this->enumerableFrom(self::$basicSampleDataWithKeys),
                function($data, $key) {
                    return $key == 'Two';
                },
                'Default',
                'B'
            ],
            'MultipleSuitableCandidates' => [
                $this->enumerableFrom(self::basicObjectData()),
                function(TestFoo $data) {
                    return $data->bar == 'Bar2';
                },
                'Default',
                new TestFoo('B', 'Bar2')
            ]
        ];
    }

    public function firstAndLastWithErrorsDataProvider() {
        return [
            'EmptyCollection' => [
                $this->enumerableFrom([]),
                function() {
                    
                }
            ],
            'NotMetCondition' => [
                $this->enumerableFrom(self::$basicSampleData),
                function($data) {
                    return $data == 'NOT VALID VALUE';
                }
            ]
        ];
    }

    public function firstDataProvider() {
        return [
            'BasicCondition' => [
                $this->enumerableFrom(self::$basicSampleData),
                function($data) {
                    return $data == 'B';
                },
                'B'
            ],
            'WithKeys' => [
                $this->enumerableFrom(self::$basicSampleDataWithKeys),
                function($data, $key) {
                    return $key == 'Two';
                },
                'B'
            ],
            'MultipleSuitableCandidates' => [
                $this->enumerableFrom(self::basicObjectData()),
                function(TestFoo $data) {
                    return $data->bar == 'Bar2';
                },
                new TestFoo('B', 'Bar2')
            ]
        ];
    }

    public function lastDataProvider() {
        return [
            'BasicCondition' => [
                $this->enumerableFrom(self::$basicSampleData),
                function($data) {
                    return $data == 'B';
                },
                'B'
            ],
            'WithKeys' => [
                $this->enumerableFrom(self::$basicSampleDataWithKeys),
                function($data, $key) {
                    return $key == 'Two';
                },
                'B'
            ],
            'MultipleSuitableCandidates' => [
                $this->enumerableFrom(self::basicObjectData()),
                function(TestFoo $data) {
                    return $data->bar == 'Bar2';
                },
                new TestFoo('BB', 'Bar2')
            ]
        ];
    }

    public function unionDataProvider() {
        return [
            'TestWithBasicComparison' => [
                $this->enumerableFrom(self::$basicSampleDataWithKeys),
                self::mixedObjectData(),
                [
                    'One' => 'A',
                    'Two' => 'B',
                    'Three' => 'C',
                    new TestFoo('A', 'Bar1'),
                    new TestFoo('B', 'Bar2'),
                    new TestFoo('C', 'Bar3'),
                    new stdClass(),
                    40,
                    new TestBar('X', 'Bar4'),
                    5
                ],
            ],
        ];
    }

    public function allDataProvider() {
        return [
            'AllObjectsComplyWithCondition' => [
                $this->enumerableFrom(self::basicObjectData()),
                function($value) {
                    return $value instanceof TestFoo;
                },
                true
            ],
            'OneObjectDoesNotComply' => [
                $this->enumerableFrom(self::mixedObjectData()),
                function($value) {
                    return is_string($value);
                },
                false
            ]
        ];
    }

    public function extendDataProvider() {
        return [
            'ExtendWithNonOverridingKeys' => [
                $this->enumerableFrom(self::$basicSampleData),
                self::$basicSampleDataWithKeys,
                [
                    'A',
                    'B',
                    'C',
                    'One' => 'A',
                    'Two' => 'B',
                    'Three' => 'C'
                ]
            ],
            'ExtendWithOverridingKeys' => [
                $this->enumerableFrom(self::basicObjectDataWithKeys()),
                self::$basicSampleDataWithKeys,
                [
                    'One' => 'A',
                    'Two' => 'B',
                    'Three' => 'C',
                    'OneOne' => new TestFoo('AA', 'Bar1'),
                    'TwoTwo' => new TestFoo('BB', 'Bar2'),
                    'ThreeThree' => new TestFoo('CC', 'Bar3'),
                ]
            ]
        ];
    }

    public function whereDataProvider() {
        return [
            'Exclude A' => [
                $this->enumerableFrom(self::$basicSampleData),
                function($value) {
                    return $value != 'A';
                },
                [1 => 'B', 2 => 'C']
            ],
            'Test with Keys' => [
                $this->enumerableFrom(self::$basicSampleDataWithKeys),
                function($value, $key) {
                    return $key != 'One';
                },
                ['Two' => 'B', 'Three' => 'C']
            ]
        ];
    }

    public function selectDataProvider() {
        return [
            'Basic Data' => [
                $this->enumerableFrom(self::$basicSampleData),
                function($value) {
                    return new TestFoo($value);
                },
                [
                    new TestFoo('A'),
                    new TestFoo('B'),
                    new TestFoo('C'),
                ]
            ],
            'Basic Data With Keys' => [
                $this->enumerableFrom(self::$basicSampleDataWithKeys),
                function($value, $key) {
                    return new TestFoo($value, $key);
                },
                [
                    'One' => new TestFoo('A', 'One'),
                    'Two' => new TestFoo('B', 'Two'),
                    'Three' => new TestFoo('C', 'Three'),
                ]
            ]
        ];
    }

    public function keysDataProvider() {
        return [
            'Numeric keys' => [
                $this->enumerableFrom(self::$basicSampleData),
                [0, 1, 2]
            ],
            'Associative keys' => [
                $this->enumerableFrom(self::$basicSampleDataWithKeys),
                ['One', 'Two', 'Three']
            ]
        ];
    }

    public function valuesDataProvider() {
        return [
            'Numeric keys' => [
                $this->enumerableFrom(self::$basicSampleData),
                ['A', 'B', 'C']
            ],
            'Associative keys' => [
                $this->enumerableFrom(self::$basicSampleDataWithKeys),
                ['A', 'B', 'C']
            ]
        ];
    }

    public function groupByDataProvider() {
        return [
            'With Basic Object Data' => [
                $this->enumerableFrom(self::basicObjectData()),
                function(TestFoo $foo) {
                    return $foo->bar;
                },
                [
                    'Bar1' => [
                        0 => new TestFoo('A', 'Bar1'),
                        3 => new TestFoo('AA', 'Bar1'),
                    ],
                    'Bar2' => [
                        1 => new TestFoo('B', 'Bar2'),
                        4 => new TestFoo('BB', 'Bar2'),
                    ],
                    'Bar3' => [
                        2 => new TestFoo('C', 'Bar3'),
                        5 => new TestFoo('CC', 'Bar3'),
                    ],
                ]
            ],
            'Object Data With Keys' => [
                $this->enumerableFrom(self::basicObjectDataWithKeys()),
                function(TestFoo $foo) {
                    return $foo->bar;
                },
                [
                    'Bar1' => [
                        'One' => new TestFoo('A', 'Bar1'),
                        'OneOne' => new TestFoo('AA', 'Bar1'),
                    ],
                    'Bar2' => [
                        'Two' => new TestFoo('B', 'Bar2'),
                        'TwoTwo' => new TestFoo('BB', 'Bar2'),
                    ],
                    'Bar3' => [
                        'Three' => new TestFoo('C', 'Bar3'),
                        'ThreeThree' => new TestFoo('CC', 'Bar3'),
                    ],
                ]
            ]
        ];
    }

    // </editor-fold>
}