<?php


namespace App\Model\Parser\UseCase\Cpanel\Task\Destroy;


use App\Model\Parser\Entity\Task;

class Command
{
    public int $taskId;

    public function __construct(Task $task)
    {
        $this->taskId = $task->getId();
    }
}