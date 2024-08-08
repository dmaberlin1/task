<?php

use PHPUnit\Framework\TestCase;
use App\Game;
use App\SlotMachine;

class GameTest extends TestCase
{
    private $game;

    protected function setUp(): void
    {
        $reels = (new Reels())->getSymbols((new Reels())->getRandomStops());
        $payoutTable = (new PayoutTable())->getPayout(8);

        $slotMachine = new SlotMachine($reels, $payoutTable);
        $this->game = new Game($slotMachine);
    }

    public function testRunWithValidBet()
    {
        ob_start();
        $this->game->run(10.0);
        $output = ob_get_clean();

        $this->assertStringContainsString("Ставка:", $output);
    }

    public function testRunWithInvalidBet()
    {
        ob_start();
        $this->game->run(-10.0);
        $output = ob_get_clean();

        $this->assertStringContainsString("Ошибка:", $output);
    }
}
