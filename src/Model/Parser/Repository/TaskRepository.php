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

    public function getProcessed()
    {
        $qb = $this->createQueryBuilder('t');

        $qb
            ->where('t.processed = :processed')
            ->setParameter(':processed', true);

        return $qb->getQuery()->getResult();
    }

    public function getForCreateProcess()
    {
        $oneHourAgo = (new \DateTime())->modify('-1 hour');

        $qb = $this->createQueryBuilder('t');

        $qb
            ->where('t.status = :status')
            ->setParameter(':status', true)
            ->andWhere('t.processed = :processed')
            ->setParameter(':processed', false)
            ->andWhere('t.updatedAt < :oneHourAgo')
            ->setParameter(':oneHourAgo', $oneHourAgo);

        return $qb->getQuery()->getResult();
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