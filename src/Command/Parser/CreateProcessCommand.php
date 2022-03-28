<?php

namespace App\Command\Parser;

use App\Model\Parser\Entity\Process;
use App\Model\Parser\Entity\Task;
use App\Model\Parser\Repository\TaskRepository;
use App\Model\Parser\UseCase\Command\Process\Create\Handler as ProcessCreateHandler;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Model\Parser\UseCase\Command\Process\Create\Command as ProcessCreateCommand;

class CreateProcessCommand extends Command
{
    protected static $defaultName = 'parser:create:process';

    private TaskRepository $taskRepository;

    private ProcessCreateHandler $handler;

    public function __construct(
        TaskRepository $taskRepository,
        ProcessCreateHandler $handler,
        string $name = null
    )
    {
        parent::__construct($name);
        $this->taskRepository = $taskRepository;
        $this->handler = $handler;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tasks = $this->taskRepository->getForCreateProcess();

        foreach ($tasks as $task) {
            $cmd = new ProcessCreateCommand($task);
            $this->handler->handle($cmd);
        }

        return Command::SUCCESS;
    }
}
