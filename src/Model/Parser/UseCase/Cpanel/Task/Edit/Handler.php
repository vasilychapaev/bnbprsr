<?php


namespace App\Model\Parser\UseCase\Cpanel\Task\Edit;


use App\Model\Flusher;
use App\Model\Parser\Repository\TaskRepository;

class Handler
{
    private TaskRepository $taskRepository;

    private Flusher $flusher;

    public function __construct(TaskRepository $taskRepository, Flusher $flusher)
    {
        $this->taskRepository = $taskRepository;
        $this->flusher = $flusher;
    }

    public function handle(Command $command)
    {
        $task = $this->taskRepository->find($command->id);

        if (null === $task){
            throw new \DomainException('Task not found');
        }

        $task->update($command->contract, $command->status);

        $this->flusher->flush();
    }
}