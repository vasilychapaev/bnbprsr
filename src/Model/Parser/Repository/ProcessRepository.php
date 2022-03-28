<?php


namespace App\Model\Parser\Repository;


use App\Model\Parser\Entity\Process;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProcessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Process::class);
    }

    public function add(Process $process){
        $this->_em->persist($process);
    }

    public function remove(Process $process){
        $this->_em->remove($process);
    }
}