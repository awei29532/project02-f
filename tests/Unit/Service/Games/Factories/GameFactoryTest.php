<?php

declare(strict_types=1);

namespace Tests\Unit\Service\Games\Factories;

use App\Service\Games\Factories\GameFactory;
use App\Service\Games\GameInterface;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Service\Games\Factories\GameFactory
 */
class GameFactoryTest extends TestCase
{
    public function gameCodeProvider()
    {
        return [
            [
                'S128Cock'
            ]
        ];
    }

    /**
     * @dataProvider gameCodeProvider
     * @param string $gameCode
     * @covers ::getInstance
     * @covers \App\Service\Games\S128Cock::__construct
     */
    public function testGetInstance(string $gameCode)
    {
        // arrange
        $target = new GameFactory();
        $expectedInterface = GameInterface::class;

        // act
        $result = $target->getInstance($gameCode);

        // assert
        self::assertInstanceOf($expectedInterface, $result);
    }

    /**
     * @covers ::getInstance
     */
    public function testGetInstanceWithUnknownGameCode()
    {
        // arrange
        $target = new GameFactory();
        $gameCode = 'hjgd56d4g6d';

        // act
        $result = $target->getInstance($gameCode);

        // assert
        self::assertNull($result);
    }
}
