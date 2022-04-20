<?php


namespace App\Model\Parser\UseCase\Command\Process\Update;


use App\Model\Blockchain\Entity\Transaction;
use App\Model\Blockchain\Repository\TransactionRepository;
use App\Model\Flusher;
use App\Model\Parser\Entity\Process;
use App\Model\Parser\Repository\BsScanRepository;
use App\Model\Parser\Repository\ProcessRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Handler
{
    private BsScanRepository $bsScanRepository;

    private ProcessRepository $processRepository;

    private TransactionRepository $transactionRepository;

    private HttpClientInterface $client;

    private Flusher $flusher;

    public function __construct(
        BsScanRepository $bsScanRepository,
        ProcessRepository $processRepository,
        TransactionRepository $transactionRepository,
        HttpClientInterface $client,
        Flusher $flusher
    )
    {
        $this->bsScanRepository = $bsScanRepository;
        $this->processRepository = $processRepository;
        $this->transactionRepository = $transactionRepository;
        $this->client = $client;
        $this->flusher = $flusher;
    }

    public function handle(Command $cmd)
    {
        $process = $this->processRepository->find($cmd->processId);

        /** @var Process $process */
        if (null === $process) {
            throw new \DomainException('Process not found.');
        }

        $task = $process->getTask();

        if (null === $task){
            throw new \DomainException('Task not found.');
        }

        foreach ($cmd->transactions as $hash) {

            $data = $this->bsScanRepository->getTransactionByHash($hash);

            $transaction = new Transaction(
                $task,
                $process,
                $data->hash,
                $data->status,
                $data->method,
                $data->block,
                $data->hash,
                $data->toWallet1,
                $data->toWallet2,
                $data->toWallet3,
                $data->toWallet4,
                $data->fee,
                $data->feeUSD,
                $data->value1,
                $data->value2,
                $data->value3,
                $data->value4,
                $data->valueUSD,
                $data->currency,
                $data->toWalletJson,
                $data->dateTime,
                $data->raw
            );

            $this->transactionRepository->add($transaction);
        }

        $process->incrementProcessed(count($cmd->transactions));

        if ($process->getProcessed() === $process->getTotal()){
            $task->finishProcess();
            $process->updateStatus(Process::STATUS_FINISH);
        }else{
            $process->updateStatus(Process::STATUS_PROCESSED);
        }

        $this->flusher->flush();
    }
}