<?php

require 'vendor/autoload.php';

use App\PayoutTable;
use App\Reels;
use App\SlotMachine;
use App\Game;
use App\Exceptions\InvalidBetException;

try {
    $payoutTable = new PayoutTable();
    $reels = new Reels();
    $slotMachine = new SlotMachine($reels->getSymbols($reels->getRandomStops()), $payoutTable);
    $game = new Game($slotMachine);

    // Запуск игры с допустимой ставкой
    $game->run(10.0);

    // Попытка запуска игры с некорректной ставкой
    $game->run(-5.0);
} catch (InvalidBetException $e) {
    echo "Поймано исключение: " . $e->getMessage() . PHP_EOL;
}
