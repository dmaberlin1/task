<?php

use PHPUnit\Framework\TestCase;
use App\SlotMachine;
use App\PayoutTable;
use App\Reels;
use App\Exceptions\InvalidBetException;

class SlotMachineTest extends TestCase
{
    private $slotMachine;

    protected function setUp(): void
    {
        $reels = (new Reels())->getSymbols((new Reels())->getRandomStops());
        $payoutTable = (new PayoutTable())->getPayout(8);

        $this->slotMachine = new SlotMachine($reels, $payoutTable);
    }

    public function testInvalidBet()
    {
        $this->expectException(InvalidBetException::class);
        $this->slotMachine->play(-5);
    }

    public function testValidPlay()
    {
        $bet = 10.0;

        ob_start();
        $this->slotMachine->play($bet);
        $output = ob_get_clean();

        $this->assertStringContainsString("Ставка: €" . number_format($bet, 2), $output);
    }
}
