<?php

declare(strict_types=1);

namespace Tests\Unit\Service;

use App\Models\Member;
use App\Service\Games\GameInterface;
use App\Service\GameService;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Service\GameService
 */
class GameServiceTest extends TestCase
{
    /**
     * @return string[][][]
     */
    public function gameCodeListProvider()
    {
        return [
            [
                ['S128Cock', 'SaSlot']
            ]
        ];
    }

    /**
     * @return Member
     */
    private function getPlayerStub()
    {
        $playerStub = $this->createStub(Member::class);
        $valueMap = [
            ['username', 'test001'],
        ];

        $playerStub->method('__get')
            ->willReturnMap($valueMap);
        return $playerStub;
    }

    /**
     * @dataProvider gameCodeListProvider
     * @param string[] $gameCodeList
     * @covers ::__construct
     * @covers ::getBalance
     */
    public function testGetBalance(array $gameCodeList)
    {
        // arrange
        $playerStub = $this->getPlayerStub();
        $target = new GameService($playerStub);

        $gameCollection = [];
        foreach ($gameCodeList as $gameCode) {
            $gameStub = $this->createStub(GameInterface::class);
            $gameStub->method('getBalance')->willReturn(1.23);
            $gameStub->method('getGameCode')->willReturn($gameCode);
            $gameCollection[] = $gameStub;
        }

        // act
        $result = $target->getBalance($gameCollection);

        // assert
        foreach ($gameCodeList as $gameCode) {
            self::assertArrayHasKey($gameCode, $result);
        }

        foreach ($result as $value) {
            self::assertIsFloat($value);
        }
    }

    /**
     * @covers ::__construct
     * @covers ::deposit
     */
    public function testDeposit()
    {
        // arrange
        $playerStub = $this->getPlayerStub();
        $target = new GameService($playerStub);
        $gameStub = $this->createStub(GameInterface::class);

        $expectedResult = true;
        $depositResult = [
            'result' => $expectedResult,
            'balance' => 1.97
        ];
        $gameStub->method('deposit')->willReturn($depositResult);
        $amount = 1.23;

        // act
        $result = $target->deposit($gameStub, $amount);

        // assert
        self::assertSame($expectedResult, $result);
    }

    /**
     * @covers ::__construct
     * @covers ::getBalance
     */
    public function testGetBalanceWithNoGameGiven()
    {
        // arrange
        $playerStub = $this->getPlayerStub();
        $target = new GameService($playerStub);
        $gameList = [];

        // act
        $result = $target->getBalance($gameList);

        // assert
        self::assertIsArray($result);
        self::assertEmpty($result);
    }

    /**
     * @covers ::__construct
     * @covers ::launchGame
     */
    public function testLaunchGame()
    {
        // arrange
        $playerStub = $this->getPlayerStub();
        $target = new GameService($playerStub);

        $gameStub = $this->createStub(GameInterface::class);
        $gameLinkInfo = [
            'url' => 'https://test.com?user=test001',
            'method' => 'GET'
        ];
        $gameStub->method('launch')->willReturn($gameLinkInfo);

        // act
        $result = $target->launchGame($gameStub);

        // assert
        self::assertIsArray($result);
        self::assertArrayHasKey('url', $result);
        self::assertArrayHasKey('method', $result);
    }

    /**
     * @dataProvider gameCodeListProvider
     * @covers ::__construct
     * @covers ::withdraw
     * @param array $gameCodeList
     */
    public function testWithdraw(array $gameCodeList)
    {
        // arrange
        $playerStub = $this->getPlayerStub();
        $target = new GameService($playerStub);
        $amount = 1.25;
        $expectedCount = count($gameCodeList);

        $gameList = [];
        foreach ($gameCodeList as $gameCode) {
            $gameStub = $this->createStub(GameInterface::class);
            $gameStub->method('withdraw')->willReturn($amount);
            $gameStub->method('getGameCode')->willReturn($gameCode);
            $gameList[] = $gameStub;
        }

        // act
        $result = $target->withdraw($gameList);

        // assert
        self::assertIsArray($result);

        foreach ($gameCodeList as $gameCode) {
            self::assertArrayHasKey($gameCode, $result);
        }
        self::assertCount($expectedCount, $result);
    }

    /**
     * @covers ::__construct
     * @covers ::withdraw
     */
    public function testWithdrawWithNoGameGiven()
    {
        // arrange
        $playerStub = $this->getPlayerStub();
        $target = new GameService($playerStub);
        $gameList = [];

        // act
        $result = $target->withdraw($gameList);

        // assert
        self::assertIsArray($result);
        self::assertEmpty($result);
    }
}
