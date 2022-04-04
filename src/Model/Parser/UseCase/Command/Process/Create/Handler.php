<?php


namespace App\Model\Parser\UseCase\Command\Process\Create;


use App\Model\EventDispatcher;
use App\Model\Flusher;
use App\Model\Parser\Entity\Process;
use App\Model\Parser\Event\Process\ProcessPart;
use App\Model\Parser\Repository\BsScanRepository;
use App\Model\Parser\Repository\ProcessRepository;

class Handler
{
    private BsScanRepository $bsScanRepository;

    private EventDispatcher $eventDispatcher;

    private Flusher $flusher;

    private ProcessRepository $processRepository;

    public function __construct(
        BsScanRepository $bsScanRepository,
        ProcessRepository $processRepository,
        EventDispatcher $eventDispatcher,
        Flusher $flusher
    )
    {
        $this->bsScanRepository = $bsScanRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->flusher = $flusher;
        $this->processRepository = $processRepository;
    }

    public function handle(Command $cmd){

        $task = $cmd->task;

        $task->startProcess();

        $this->flusher->flush();

        $events = [];

        $hashes = $this->bsScanRepository->getIdsByContract(
            $task->getContract(),
            $task->getLastTransactionHash()
        );

        $process = new Process(
            $task,
            count($hashes),
        );

        $processStatus = count($hashes) === 0 ? Process::STATUS_FINISH : Process::STATUS_WAIT;

        $process->updateStatus($processStatus);

        $task->updateLastTransactionHash($hashes[0] ?? $task->getLastTransactionHash());

        $task->startProcess();

        $this->processRepository->add($process);

        $this->flusher->flush();

        $chunkedHashes = array_chunk($hashes, 20);

        foreach ($chunkedHashes as $chunk) {
            array_push($events, new ProcessPart($process->getId(), $chunk));
        }

        $this->eventDispatcher->dispatch($events);
    }
}