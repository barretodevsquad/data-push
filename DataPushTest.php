<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class DataPushTest extends TestCase
{
    private array $array;

    public function setUp(): void
    {
        $this->array = [
            'arr' => [
                'a' => [
                    'a-1' => [
                        'a-2' => 'v-a2',
                    ],
                ],
                'b' => [
                    'b-1' => [
                        'b-2' => 'v-b2',
                    ],
                ],
            ],
        ];
    }

    /**
     * @test
     * @covers data_push
     */
    public function it_should_add_new_array_inside_an_array(): void
    {
        $expectedArray = $this->array;
        array_push($expectedArray['arr'], 'test');

        data_push($this->array, 'arr', 'test');
        $this->assertEquals($this->array, $expectedArray);
    }

    /**
     * @test
     * @covers data_push
     */
    public function it_should_add_new_array_inside_an_array_with_custom_index(): void
    {
        $expectedArray                        = $this->array;
        $expectedArray['arr']['custom_index'] = 'test';

        data_push($this->array, 'arr', 'test', index: 'custom_index');
        $this->assertEquals($this->array, $expectedArray);
    }

    /**
     * @test
     * @covers data_push
     */
    public function it_should_add_new_array_inside_unknow_array_index(): void
    {
        $expectedArray = $this->array;

        array_push($expectedArray['arr'], 'arr2');
        array_push($expectedArray['arr']['a'], 'c');
        array_push($expectedArray['arr']['a']['a-1'], 'a-2-1');

        data_push($this->array, 'arr', 'arr2');
        data_push($this->array, 'arr.*', 'c');
        data_push($this->array, 'arr.a.*', 'a-2-1');

        $this->assertEquals($this->array, $expectedArray);
    }

    /**
     * @test
     * @covers data_push
     */
    public function it_should_add_new_array_inside_unknow_array_index_recursivelly(): void
    {
        $expectedArray = $this->array;
        array_push($expectedArray['arr']['a']['a-1'], 'c');
        array_push($expectedArray['arr']['b']['b-1'], 'c');

        data_push($this->array, 'arr.*.*', 'c', recursive: true);

        $this->assertEquals($this->array, $expectedArray);
    }

    /**
     * @test
     * @covers data_push
     */
    public function it_should_add_new_array_inside_unknow_array_index_with_custom_index_recursivelly(): void
    {
        $expectedArray = $this->array;

        $expectedArray['arr']['a']['a-1']['custom_index'] = 'c';
        $expectedArray['arr']['b']['b-1']['custom_index'] = 'c';

        data_push($this->array, 'arr.*.*', 'c', index: 'custom_index', recursive: true);

        $this->assertEquals($this->array, $expectedArray);
    }

    /**
     * @test
     * @covers data_push
     */
    public function it_should_be_able_to_walk_through_unknow_indexes(): void
    {
        $expectedArray = $this->array;
        array_push($expectedArray['arr']['a']['a-1'], 'a-2-1');

        data_push($this->array, '*.*.*', 'a-2-1');

        $this->assertEquals($this->array, $expectedArray);
    }

    /**
     * @test
     * @covers data_push
     */
    public function it_should_throw_error_when_index_does_not_exists(): void
    {
        $this->expectExceptionMessage('given key is not an array or the key does not exist');
        data_push($this->array, 'this_key_doesnt_exists', 'value');
    }

    /**
     * @test
     * @covers data_push
     */
    public function it_should_throw_error_when_index_is_not_an_array(): void
    {
        $this->expectExceptionMessage('given key is not an array or the key does not exist');
        data_push($this->array, 'arr.a.a-1.a-2', 'value');
    }
}
