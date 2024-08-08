<?php

namespace App;

/**
 * Класс для управления таблицей выплат.
 */
class PayoutTable {
    /**
     * @var array $payoutTable Таблица выплат, где ключ - это символ, а значение - размер выплаты.
     */
    private $payoutTable;


    /**
     * PayoutTable constructor.
     * Инициализирует таблицу выплат.
     */
    public function __construct() {
        $this->payoutTable = [
            8 => 10, // purple bear
            6 => 5,  // star
            9 => 15, // orange bear
            1 => 3   // others
        ];
    }

    /**
     * Возвращает размер выплаты для заданного символа.
     *
     * @param int $symbol Символ.
     * @return int Размер выплаты.
     */
    public function getPayout($symbol) {
        return isset($this->payoutTable[$symbol]) ? $this->payoutTable[$symbol] : 0;
    }
}

?>
