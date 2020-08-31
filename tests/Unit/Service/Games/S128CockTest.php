<?php

declare(strict_types=1);

namespace Tests\Unit\Service\Games;

use App\Service\Games\S128Cock;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Service\Games\S128Cock
 */
class S128CockTest extends TestCase
{
    private const GAME_CODE = 'S128Cock';

    /**
     * @covers ::__construct
     * @covers ::getBalance
     * @covers ::sendPostRequest
     * @covers ::fetchValueOfXmlElement
     * @covers ::getDataListFromXmlString
     * @covers ::getXmlElementFromString
     */
    public function testGetBalance()
    {
        // arrange
        $target = new S128Cock(self::GAME_CODE);
        $playerId = $this->getPlayerId();

        // act
        $result = $target->getBalance($playerId);

        // assert
        self::assertIsFloat($result);
    }

    /**
     * @covers ::__construct
     * @covers ::deposit
     * @covers ::sendPostRequest
     * @covers ::fetchValueOfXmlElement
     * @covers ::getDataListFromXmlString
     * @covers ::getXmlElementFromString
     */
    public function testDeposit()
    {
        // arrange
        $target = new S128Cock(self::GAME_CODE);
        $playerId = $this->getPlayerId();
        $amount = 1.23;

        // act
        $result = $target->deposit($playerId, $amount);

        // assert
        $this->assertDepositResult($result);
    }

    /**
     * @covers ::__construct
     * @covers ::withdraw
     * @covers ::getBalance
     * @covers ::sendPostRequest
     * @covers ::fetchValueOfXmlElement
     * @covers ::getDataListFromXmlString
     * @covers ::getXmlElementFromString
     */
    public function testWithdrawAllBalance()
    {
        // arrange
        $target = new S128Cock(self::GAME_CODE);
        $playerId = $this->getPlayerId();

        // act
        $result = $target->withdraw($playerId);

        // assert
        self::assertIsFloat($result);
    }

    /**
     * @covers ::__construct
     * @covers ::deposit
     * @covers ::sendPostRequest
     * @covers ::fetchValueOfXmlElement
     * @covers ::getDataListFromXmlString
     * @covers ::getXmlElementFromString
     */
    public function testDepositWithUnregisteredPlayer()
    {
        // arrange
        $target = new S128Cock(self::GAME_CODE);
        $playerId = $this->getPlayerId(false);
        $amount = 1.01;

        // act
        $result = $target->deposit($playerId, $amount);

        // assert
        $this->assertDepositResult($result);
    }

    /**
     * @covers ::__construct
     * @covers ::launch
     * @covers ::getSessionId
     * @covers ::sendPostRequest
     * @covers ::fetchValueOfXmlElement
     * @covers ::getDataListFromXmlString
     * @covers ::getXmlElementFromString
     */
    public function testLaunch()
    {
        // arrange
        $target = new S128Cock(self::GAME_CODE);
        $playerId = $this->getPlayerId();

        // act
        $result = $target->launch($playerId);

        // assert
        $this->assertLaunchResult($result);
    }

    /**
     * @covers ::__construct
     * @covers ::getGameCode
     */
    public function testGetGameCode()
    {
        // arrange
        $gameCode = self::GAME_CODE;
        $target = new S128Cock($gameCode);

        // act
        $result = $target->getGameCode();

        // assert
        self::assertSame($gameCode, $result);
    }

    /**
     * @param bool $isRegistered
     * @return string
     */
    private function getPlayerId(bool $isRegistered = true)
    {
        $playerId = 'test001';

        if (!$isRegistered) {
            $playerId = 'u' . (microtime(true) * 10000);
        }

        return $playerId;
    }

    private function assertDepositResult(array $depositResult)
    {
        $resultKey = 'result';
        self::assertArrayHasKey($resultKey, $depositResult);
        self::assertIsBool($depositResult[$resultKey]);

        $balanceKey = 'balance';
        self::assertArrayHasKey($balanceKey, $depositResult);
        self::assertIsFloat($depositResult[$balanceKey]);
    }

    private function assertLaunchResult(array $launchResult)
    {
        $keys = [
            'url',
            'method',
            'body'
        ];

        foreach ($keys as $key) {
            self::assertArrayHasKey($key, $launchResult);
        }

        $bodyKeys = [
            'session_id',
            'login_id'
        ];

        foreach ($bodyKeys as $key) {
            self::assertArrayHasKey($key, $launchResult['body']);
        }
    }
}
