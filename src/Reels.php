<?php

namespace App;

/**
 * Класс для управления катушками.
 */
class Reels {
    private $reels;

    public function __construct() {
        // Определение 7 катушек
        $this->reels = array_fill(0, 7, [9,9,3,3,6,7,6,7,5,1,6,9,4,4,5,3,9,8,8,4,6,9,9,5,5,8,8,7,7,9,6,8,7,5,5,9,8,7,9,6,4,6,9,8,7,6,6,9,9,8,6,9,8,9,4,9,9,7,6,9,9,8,3,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,8,4,5,3,7,8,8,7,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,8,9,8,8,4,7,9,7,6,9,9,8,5,3,7,7,8,6,9,7,6,8,5,5,4,5,3,7,7,8,4,5,7,4,8,8,6,6,6]);
    }

    /**
     * Генерация случайной точки остановки для каждой катушки.
     *
     * @return int[] Массив точек остановки для каждой катушки.
     */
    public function getRandomStops() {
        return array_map(fn($reel) => rand(0, count($reel) - 1), $this->reels);
    }

    /**
     * Получение символов для игрового поля.
     *
     * @param int[] $stops Массив точек остановки.
     * @return int[][] Массив символов для игрового поля.
     */
    public function getSymbols(array $stops) {
        return array_map(
            fn($index, $stop) => $this->getSymbolsFromReel($this->reels[$index], $stop, 7),
            array_keys($this->reels),
            $stops
        );
    }

    /**
     * Получение символов с одной катушки.
     *
     * @param int[] $reel Катушка.
     * @param int $stop Точка остановки.
     * @param int $length Количество символов.
     * @return int[] Массив символов.
     */
    private function getSymbolsFromReel(array $reel, int $stop, int $length) {
        return array_map(
            fn($i) => $reel[($stop + $i) % count($reel)],
            range(0, $length - 1)
        );
    }
}
