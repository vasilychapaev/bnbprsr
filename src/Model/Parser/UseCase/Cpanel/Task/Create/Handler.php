<?php


namespace App\Model\Parser\UseCase\Cpanel\Task\Create;


use App\Model\Blockchain\Repository\TransactionRepository;
use App\Model\Flusher;
use App\Model\Parser\Entity\Task;
use App\Model\Parser\Repository\TaskRepository;

class Handler
{
    private TaskRepository $taskRepository;

    private Flusher $flusher;

    private TransactionRepository $transactionRepository;

    public function __construct(
        TaskRepository $taskRepository,
        TransactionRepository $transactionRepository,
        Flusher $flusher
    )
    {
        $this->taskRepository = $taskRepository;
        $this->flusher = $flusher;
        $this->transactionRepository = $transactionRepository;
    }

    public function handle(Command $command)
    {
        $task = $this->taskRepository->findBy(['contract'=>$command->contract]);

        if ($task){
            throw new \DomainException('Error, task with this contract already exists');
        }

        $task = new Task($command->contract, $command->status, $command->title, $command->description);
        $this->taskRepository->add($task);
        $this->transactionRepository->createViewForTask($task);
        $this->flusher->flush();
    }
}