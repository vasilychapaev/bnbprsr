<?php


namespace App\Model\Parser\Repository;

use App\Model\Parser\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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
            ->andWhere('t.lastProcessAt < :oneHourAgo OR t.lastProcessAt IS NULL')
            ->setParameter(':oneHourAgo', $oneHourAgo);

        return $qb->getQuery()->getResult()[0] ?? null;
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