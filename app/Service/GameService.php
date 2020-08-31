<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\Member;
use App\Service\Games\GameInterface;

class GameService
{
    // TODO: Check if Member is necessary. Or just need the username
    private Member $player;

    public function __construct(Member $player)
    {
        $this->player = $player;
    }

    /**
     * @param GameInterface[] $gameCollection
     * @return array
     */
    public function getBalance(array $gameCollection)
    {
        // TODO: get balances from all game included in the list
        $balanceList = [];
        foreach ($gameCollection as $game) {
            $balanceList[$game->getGameCode()] = $game->getBalance($this->player->username);
        }

        return $balanceList;
    }

    public function launchGame(GameInterface $game)
    {
        // TODO: Implement launchGame() method.
        $gameLinkInfo = $game->launch($this->player->username);

        // $gameGetLink = [
        //     'url' => 'https://example.com/?user=test001&type=slot&game=001',
        //     'method' => 'GET',
        //     'headers' => [],
        //     'body' => []
        // ];

        return $gameLinkInfo;
    }

    /**
     * Put the points into the account in the game.
     *
     * The account in the game will be created automatically if it's nonexistent.
     *
     * @param GameInterface $game
     * @param float $amount
     * @return bool True on success, false otherwise
     */
    public function deposit(GameInterface $game, float $amount): bool
    {
        $depositResult = $game->deposit($this->player->username, $amount);

        return $depositResult['result'];
    }

    /**
     * @param GameInterface[] $gameCollection
     * @param float|null $amount
     * @return array
     */
    public function withdraw(array $gameCollection, float $amount = null)
    {
        $pointList = [];
        foreach ($gameCollection as $game) {
            $pointList[$game->getGameCode()] = $game->withdraw($this->player->username, $amount);
        }

        return $pointList;
    }
}
