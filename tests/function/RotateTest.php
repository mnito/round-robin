<?php

use \PHPUnit\Framework\TestCase;

class RotateTest extends TestCase
{
    public function testZeroElementRotation()
    {
        $zero = [];
        rotate($zero);
        $this->assertEquals([], $zero);
    }

    public function testOneElementRotation()
    {
        $one = [1];
        rotate($one);
        $this->assertEquals([1], $one);
    }

    public function testTwoElementRotation()
    {
        $two = [1, 2];
        rotate($two);
        $this->assertEquals([1, 2], $two);
    }

    public function testThreeElementRotation()
    {
        $three = [1, 2, 3];
        rotate($three);
        $this->assertEquals([1, 3, 2], $three);
        rotate($three);
        $this->assertEquals([1, 2, 3], $three);
    }

    public function testFourElementRotation()
    {
        $even = [
            1, 2,
            3, 4
        ];
        $rotations = [
            [
                1, 3,
                4, 2
            ],
            [
                1, 4,
                2, 3
            ],
            [
                1, 2,
                3, 4
            ]
        ];
        foreach($rotations as $rotation) {
            rotate($even);
            $this->assertEquals($rotation, $even);
        }
    }

    public function testArbitraryEvenNumberElementRotation()
    {
        $even = [
            0, 1, 2, 3, 4,
            5, 6, 7, 8, 9
        ];
        $rotations = [
            [
                0, 5, 1, 2, 3,
                6, 7, 8, 9, 4
            ],
            [
                0, 6, 5, 1, 2,
                7, 8, 9, 4, 3
            ],
            [
                0, 7, 6, 5, 1,
                8, 9, 4, 3, 2
            ],
            [
                0, 8, 7, 6, 5,
                9, 4, 3, 2, 1
            ],
            [
                0, 9, 8, 7, 6,
                4, 3, 2, 1, 5
            ],
            [
                0, 4, 9, 8, 7,
                3, 2, 1, 5, 6
            ],
            [
                0, 3, 4, 9, 8,
                2, 1, 5, 6, 7
            ],
            [
                0, 2, 3, 4, 9,
                1, 5, 6, 7, 8
            ],
            [
                0, 1, 2, 3, 4,
                5, 6, 7, 8, 9
            ]
        ];
        foreach($rotations as $rotation) {
            rotate($even);
            $this->assertEquals($even, $rotation);
        }
    }

    public function testArbitraryOddNumberElementRotation()
    {
        $odd = [
            1, 2, 3, 4, 5,
               6, 7, 8, 9
        ];
        $rotations = [
            [
                1, 6, 2, 3, 4,
                   7, 8, 9, 5
            ],
            [
                1, 7, 6, 2, 3,
                   8, 9, 5, 4
            ],
            [
                1, 8, 7, 6, 2,
                   9, 5, 4, 3
            ],
            [
                1, 9, 8, 7, 6,
                   5, 4, 3, 2
            ],
            [
                1, 5, 9, 8, 7,
                   4, 3, 2, 6
            ],
            [
                1, 4, 5, 9, 8,
                   3, 2, 6, 7
            ],
            [
                1, 3, 4, 5, 9,
                   2, 6, 7, 8
            ],
            [
                1, 2, 3, 4, 5,
                   6, 7, 8, 9
            ]
        ];
        foreach($rotations as $rotation) {
            rotate($odd);
            $this->assertEquals($rotation, $odd);
        }
    }
}
