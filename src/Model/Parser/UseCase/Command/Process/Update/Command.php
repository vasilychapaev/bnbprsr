<?php


namespace App\Model\Parser\UseCase\Command\Process\Update;


class Command
{
    public int $processId;

    public array $transactions;

    public function __construct(int $processId, array $transactions)
    {
        $this->processId = $processId;
        $this->transactions = $transactions;
    }
}