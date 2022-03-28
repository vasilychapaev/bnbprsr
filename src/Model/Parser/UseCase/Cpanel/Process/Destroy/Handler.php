<?php


namespace App\Model\Parser\UseCase\Cpanel\Process\Destroy;


use App\Model\Flusher;
use App\Model\Parser\Repository\ProcessRepository;

class Handler
{
    private ProcessRepository $processRepository;

    private Flusher $flusher;

    public function __construct(ProcessRepository $processRepository, Flusher $flusher)
    {

        $this->processRepository = $processRepository;
        $this->flusher = $flusher;
    }

    public function handle(Command $command)
    {
        $process = $this->processRepository->find($command->processId);

        if (null === $process){
            throw new \DomainException('Process not found');
        }

        $this->processRepository->remove($process);
        $this->flusher->flush();
    }
}