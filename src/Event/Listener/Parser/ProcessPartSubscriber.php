<?php


namespace App\Event\Listener\Parser;


use App\Model\Parser\Event\Process\ProcessPart;
use App\Model\Parser\UseCase\Command\Process\Update\Command;
use App\Model\Parser\UseCase\Command\Process\Update\Handler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


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