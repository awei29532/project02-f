<?php

namespace App\Service\Games;

interface GameInterface
{
    // TODO: Define the return value format

    public function getBalance(string $playerId);

    public function deposit(string $playerId, float $amount): array;

    public function withdraw(string $playerId, float $amount = null);

    public function launch(string $playerId);

    public function getGameCode(): string;
}
