<?php

namespace App;

/**
 * Game управляет процессом игры на игровом автомате.
 */
class Game
{
    /**
     * @var SlotMachine $slotMachine Объект игрового автомата.
     */
    private $slotMachine;

    /**
     * Конструктор класса Game.
     *
     * @param SlotMachine $slotMachine Объект игрового автомата.
     */
    public function __construct(SlotMachine $slotMachine)
    {
        $this->slotMachine = $slotMachine;
    }

    /**
     * Запускает игровой процесс.
     *
     * @param float $bet Ставка игрока.
     * @return void
     */
    public function run(float $bet): void
    {
        try {
            $result = $this->slotMachine->play($bet);
            $this->displayResult($result);
        } catch (\Exception $e) {
            echo "Ошибка: " . $e->getMessage() . PHP_EOL;
        }
    }

    /**
     * Выводит результат игры в консоль.
     *
     * @param array $result Результат игры.
     * @return void
     */
    private function displayResult(array $result): void
    {
        echo "Ставка: " . $result['bet'] . PHP_EOL;
        echo "Игровое поле:" . PHP_EOL;
        foreach ($result['field'] as $column) {
            echo implode(' ', $column) . PHP_EOL;
        }
        echo "Выигрыш: " . $result['winnings'] . PHP_EOL;
    }
}
