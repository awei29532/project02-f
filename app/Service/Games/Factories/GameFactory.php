<?php

namespace App\Service\Games\Factories;

use App\Service\Games\GameInterface;
use App\Service\Games\S128Cock;

class GameFactory
{
    // TODO: check the actual gameCodes

    private const GAME_INSTANCE_MAP = [
        'S128Cock' => S128Cock::class
    ];

    /**
     * @param string $gameCode
     * @return GameInterface|null
     */
    public function getInstance(string $gameCode): ?GameInterface
    {
        $className = self::GAME_INSTANCE_MAP[$gameCode] ?? null;

        if (!isset($className)) {
            return null;
        }

        return new $className($gameCode);
    }
}
