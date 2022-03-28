<?php


namespace App\Model\Parser\Event\Process;


class ProcessPart
{
    private int $processId;

    private array $transactions;

    public function __construct(int $processId, array $transactions)
    {
        $this->transactions = $transactions;
        $this->processId = $processId;
    }

    public function getTransactions(): array
    {
        return $this->transactions;
    }

    public function getProcessId(): int
    {
        return $this->processId;
    }
}