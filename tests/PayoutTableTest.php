<?php

use PHPUnit\Framework\TestCase;

class PayoutTableTest extends TestCase
{
    public function testGetPayout()
    {
        $payoutTable = new PayoutTable();

        $this->assertEquals(10, $payoutTable->getPayout(8));
        $this->assertEquals(5, $payoutTable->getPayout(6));
        $this->assertEquals(15, $payoutTable->getPayout(9));
        $this->assertEquals(3, $payoutTable->getPayout(1));
        $this->assertEquals(0, $payoutTable->getPayout(999)); // Несуществующий символ
    }
}
