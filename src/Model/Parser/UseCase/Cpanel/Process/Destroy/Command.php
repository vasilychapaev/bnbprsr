<?php


namespace App\Model\Parser\UseCase\Cpanel\Process\Destroy;


use App\Model\Parser\Entity\Process;

class Command
{
    public ?int $processId;

    public function __construct(Process $process)
    {
        $this->processId = $process->getId();
    }
}