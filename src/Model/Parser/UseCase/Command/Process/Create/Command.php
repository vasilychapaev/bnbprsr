<?php


namespace App\Model\Parser\UseCase\Command\Process\Create;


use App\Model\Parser\Entity\Task;

class Command
{
    public Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }
}