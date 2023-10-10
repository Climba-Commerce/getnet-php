<?php
namespace Tests;

use Getnet\API\Transaction;

final class TransactionTest extends TestBase
{

    public function testTransactionAmount(): void
    {
        $transaction = new Transaction();
        $this->assertNull($transaction->getAmount());

        $transaction->setAmount(76.89);
        $this->assertSame(7689, $transaction->getAmount());

        $transaction->setAmount(2428.72);
        $this->assertSame(242872, $transaction->getAmount());

        $transaction->setAmount('2428.72');
        $this->assertSame(242872, $transaction->getAmount());

        $transaction->setAmount(299.9);
        $this->assertSame(29990, $transaction->getAmount());

        $transaction->setAmount('299.9');
        $this->assertSame(29990, $transaction->getAmount());

        $transaction->setAmount('76.89');
        $this->assertSame(7689, $transaction->getAmount());
        
        $transaction->setAmount(7628.89);
        $this->assertSame(762889, $transaction->getAmount());
        $transaction->setAmount('7628.89');
        $this->assertSame(762889, $transaction->getAmount());
        
        $transaction->setAmount(10000);
        $this->assertSame(1000000, $transaction->getAmount());
        $transaction->setAmount('10000');
        $this->assertSame(1000000, $transaction->getAmount());
    }
}