<?php


namespace App\Model\Parser\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Model\Parser\Repository\TaskRepository")
 * @ORM\Table(name="parser_tasks")
 */
class Task
{
    const STATUS_ACTIVE = true;

    const STATUS_INACTIVE = false;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Model\Blockchain\Entity\Transaction", mappedBy="task")
     */
    private Collection $transactions;

    /**
     * @ORM\Column(type="string")
     */
    private string $contract;

    /**
     * @ORM\OneToMany(targetEntity="Process", mappedBy="task")
     */
    private Collection $processes;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $status;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $lastTransactionHash;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface  $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private \DateTimeInterface $updatedAt;

    public function __construct(string $contract, string $status)
    {
        $this->contract = $contract;
        $this->status = $status;
        $this->lastTransactionHash = null;
        $this->transactions = new ArrayCollection();
    }

    public function update(string $contract, string $status)
    {
        $this->contract = $contract;
        $this->status = $status;
    }

    public function updateLastTransactionHash(string $hash): self
    {
        $this->lastTransactionHash = $hash;

        return $this;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getContract(): string
    {
        return $this->contract;
    }

    public function getProcesses(): string
    {
        return $this->processes;
    }

    /**
     * @return bool|string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getLastTransactionHash(): ?string
    {
        return $this->lastTransactionHash;
    }

    public function getLasProcess(): ?Process
    {
        $process = $this->processes->last();
        return $process ? $process : null;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getTransactions(): ArrayCollection
    {
        return $this->transactions;
    }
}