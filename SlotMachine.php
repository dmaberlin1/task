<?php

namespace App;

use App\Exceptions\InvalidBetException;
use App\Exceptions\ReelOutOfBoundsException;

/**
 * SlotMachine отвечает за симуляцию игрового автомата.
 */
class SlotMachine
{
    private array $reels; // Катушки
    private array $paytable; // Таблица выплат
    private float $totalWinnings = 0.0;
    private float $totalBet = 0.0;

    public function __construct(array $reels, array $paytable)
    {
        $this->reels = $reels;
        $this->paytable = $paytable;
    }

    /**
     * Запускает игру.
     *
     * @param float $bet Сумма ставки.
     * @return void
     */
    public function play(float $bet): void
    {
        if ($bet <= 0) {
            throw new InvalidBetException("Ставка должна быть больше нуля.");
        }

        $this->totalBet += $bet;
        $stoppingPoints = $this->generateStoppingPoints();
        $field = $this->generateField($stoppingPoints);
        $winnings = $this->calculateWinnings($field);

        $this->totalWinnings += $winnings;

        $this->displayResults($bet, $field, $winnings);

        // Если есть выигрыши, перезапускаем игру до тех пор, пока выигрыши есть
        while ($winnings > 0) {
            $field = $this->updateField($field, $stoppingPoints);
            $winnings = $this->calculateWinnings($field);
            $this->totalWinnings += $winnings;

            $this->displayResults($bet, $field, $winnings);
        }

        $this->displayRTP();
    }

    /**
     * Генерирует случайные точки остановки для катушек.
     *
     * @return array Массив точек остановки.
     */
    private function generateStoppingPoints(): array
    {
        $points = [];
        foreach ($this->reels as $reel) {
            $points[] = rand(0, count($reel) - 1);
        }
        return $points;
    }

    /**
     * Генерирует игровое поле на основе точек остановки.
     *
     * @param array $stoppingPoints Точки остановки катушек.
     * @return array Игровое поле.
     */
    private function generateField(array $stoppingPoints): array
    {
        $field = [];
        foreach ($stoppingPoints as $reelIndex => $stopPoint) {
            $reel = $this->reels[$reelIndex];
            $reelLength = count($reel);
            $field[$reelIndex] = array_merge(
                array_slice($reel, $stopPoint),
                array_slice($reel, 0, $stopPoint)
            );
        }

        // Транспонируем для получения 7x7 поля
        return array_map(null, ...$field);
    }

    /**
     * Обновляет игровое поле после выигрыша.
     *
     * @param array $field Игровое поле.
     * @param array $stoppingPoints Точки остановки катушек.
     * @return array Обновленное игровое поле.
     */
    private function updateField(array $field, array $stoppingPoints): array
    {
        foreach ($field as $col => &$column) {
            $reel = $this->reels[$col];
            $stopPoint = $stoppingPoints[$col];
            $column = array_merge(
                array_slice($reel, 0, $stopPoint),
                array_slice($column, 0, 7 - $stopPoint)
            );
        }

        // Транспонируем для получения 7x7 поля
        return array_map(null, ...$field);
    }

    /**
     * Вычисляет выигрыш на основе игрового поля.
     *
     * @param array $field Игровое поле.
     * @return float Выигрыш.
     */
    private function calculateWinnings(array $field): float
    {
        $winnings = 0.0;
        $symbolWins = $this->getSymbolWins($field);

        foreach ($symbolWins as $symbol => $count) {
            if ($count >= 5) {
                $winnings += $this->getSymbolPayout($symbol) * ($count - 4); // Выигрыш за символы больше 5
            }
        }

        return $winnings;
    }

    /**
     * Находит все выигрыши в игровом поле.
     *
     * @param array $field Игровое поле.
     * @return array Массив выигрышей с символами и количеством совпадений.
     */
    private function getSymbolWins(array $field): array
    {
        $symbolWins = [];

        // Проверка горизонтальных комбинаций
        for ($row = 0; $row < 7; $row++) {
            $currentSymbol = null;
            $count = 0;
            for ($col = 0; $col < 7; $col++) {
                $symbol = $field[$col][$row];
                if ($symbol === $currentSymbol) {
                    $count++;
                } else {
                    if ($count >= 5) {
                        $symbolWins[$currentSymbol] = ($symbolWins[$currentSymbol] ?? 0) + $count;
                    }
                    $currentSymbol = $symbol;
                    $count = 1;
                }
            }
            if ($count >= 5) {
                $symbolWins[$currentSymbol] = ($symbolWins[$currentSymbol] ?? 0) + $count;
            }
        }

        // Проверка вертикальных комбинаций
        for ($col = 0; $col < 7; $col++) {
            $currentSymbol = null;
            $count = 0;
            for ($row = 0; $row < 7; $row++) {
                $symbol = $field[$col][$row];
                if ($symbol === $currentSymbol) {
                    $count++;
                } else {
                    if ($count >= 5) {
                        $symbolWins[$currentSymbol] = ($symbolWins[$currentSymbol] ?? 0) + $count;
                    }
                    $currentSymbol = $symbol;
                    $count = 1;
                }
            }
            if ($count >= 5) {
                $symbolWins[$currentSymbol] = ($symbolWins[$currentSymbol] ?? 0) + $count;
            }
        }

        return $symbolWins;
    }

    /**
     * Возвращает выплату за символ из таблицы выплат.
     *
     * @param int $symbol Символ.
     * @return float Выплата.
     */
    private function getSymbolPayout(int $symbol): float
    {
        return $this->paytable[$symbol] ?? 0.0;
    }

    /**
     * Отображает результаты игры.
     *
     * @param float $bet Ставка.
     * @param array $field Игровое поле.
     * @param float $winnings Выигрыш.
     * @return void
     */
    private function displayResults(float $bet, array $field, float $winnings): void
    {
        echo "Ставка: €" . number_format($bet, 2) . PHP_EOL;
        echo "Игровое поле:" . PHP_EOL;

        foreach ($field as $row) {
            echo implode(' ', $row) . PHP_EOL;
        }

        echo "Выигрыш: €" . number_format($winnings, 2) . PHP_EOL;
    }

    /**
     * Отображает RTP за всю игру.
     *
     * @return void
     */
    private function displayRTP(): void
    {
        $rtp = $this->totalWinnings / $this->totalBet * 100;
        echo "Общий RTP: " . number_format($rtp, 2) . "%" . PHP_EOL;
    }
}
