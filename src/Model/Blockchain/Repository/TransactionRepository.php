<?php


namespace App\Model\Blockchain\Repository;


use App\Model\Blockchain\Entity\Transaction;
use App\Model\Parser\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function createViewForTask(Task $task){
        $viewName = "transactions_{$task->getContract()}";
        $sql = "CREATE OR REPLACE VIEW `{$viewName}` AS SELECT
	            blockchain_transactions.*
                FROM
                	blockchain_transactions
                WHERE
                	blockchain_transactions.to_contract = '{$task->getContract()}'";
        try {
            $this->_em->getConnection()->executeStatement($sql);
        } catch (Exception $e) {
            throw new \DomainException($e->getMessage());
        }
    }

    public function add(Transaction $transaction)
    {
        $this->_em->persist($transaction);
    }

    public function remove(Transaction $transaction)
    {
        $this->_em->remove($transaction);
    }
}