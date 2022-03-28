<?php


namespace App\Model\Parser\Repository;

use App\Model\Parser\Entity\Process;
use App\Model\Parser\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function getForCreateProcess()
    {
        $oneHourAgo =  (new \DateTime())->modify('-1 hour');

        $qb = $this->createQueryBuilder('t');

        $qb
            ->where('t.status = :status')
            ->setParameter(':status', true);

        $tasks = new ArrayCollection($qb->getQuery()->getResult());

        return $tasks->filter(function ($item)use($oneHourAgo) {
            $process = $item->getLasProcess();

            if ($process->getStatus() === Process::STATUS_PROCESSED){
                return false;
            }

            elseif (null === $process) {
                return true;
            }

            elseif ( $process->getStatus() === Process::STATUS_FINISH && $process->getCreatedAt() < $oneHourAgo) {
                return true;
            }

            return false;
        });
    }

    public function add(Task $task)
    {
        $this->_em->persist($task);
    }

    public function remove(Task $task)
    {
        $this->_em->remove($task);
    }
}