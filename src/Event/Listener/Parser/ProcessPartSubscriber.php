<?php


namespace App\Event\Listener\Parser;


use App\Model\Blockchain\Entity\Transaction;
use App\Model\Blockchain\Repository\TransactionRepository;
use App\Model\Flusher;
use App\Model\Parser\Entity\Process;
use App\Model\Parser\Event\Process\ProcessPart;
use App\Model\Parser\Repository\BsScanRepository;
use App\Model\Parser\Repository\ProcessRepository;
use App\Model\Parser\UseCase\Command\Process\Update\Command;
use App\Model\Parser\UseCase\Command\Process\Update\Handler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProcessPartSubscriber implements EventSubscriberInterface
{
    private Handler $handler;

    public function __construct(Handler $handler)
    {

        $this->handler = $handler;
    }

    public static function getSubscribedEvents()
    {
        return [
            ProcessPart::class => [
                'execute'
            ]
        ];
    }

    public function execute(ProcessPart $part)
    {
        $cmd = new Command(
            $part->getProcessId(),
            $part->getTransactions()
        );

        $this->handler->handle($cmd);
    }
}