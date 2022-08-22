<?php

//namespace GeekBrains\Blog\UnitTests\Commands;
namespace Elena\AppBlog\Blog\UnitTests\Commands;

//use GeekBrains\Blog\Commands\Arguments;
use Elena\AppBlog\Blog\Commands\Arguments;
use Elena\AppBlog\Blog\Exceptions\ArgumentsException;
use PHPUnit\Framework\TestCase;

class ArgumentsTest extends TestCase
{
    public function testItReturnsArgumentsValueByName(): void
    {
        // Подготовка
        $arguments = new Arguments(['some_key' => 'some_value']);
        // Действие
        $value = $arguments->get('some_key');
        // Проверка
        $this->assertEquals('some_value', $value);
    }

    public function testItReturnsValueAsString(): void
    {
        $arguments = new Arguments(['some_key' => 123]);
        $value = $arguments->get('some_key');
        $this->assertSame('123', $value);
        $this->assertIsString($value);
    }



    public function testItThrowAnExceptionWhenArgumentIsAbsent(): void
    {
        $arguments = new Arguments([]);
        $this->expectException(ArgumentsException::class);
        $this->expectExceptionMessage("No such argument: some_key");
        $arguments->get('some_key');
    }

    // Провайдер данных (набор тестовых данных)
    public function argumentsProvider(): iterable
    {
        return [
            ['some_string', 'some_string'],
            ['some_string', 'some_string'],
            [123, '123'],
            [12.3, '12.3'],
        ];
    }

    /**
     * @dataProvider argumentsProvider
     */
    public function testItConvertsArgumentsToString($inputValue, $expectedValue): void
    {
        $arguments = new Arguments(['some_key' => $inputValue]);
        $value = $arguments->get('some_key');
        $this->assertEquals($expectedValue, $value);
    }
}
