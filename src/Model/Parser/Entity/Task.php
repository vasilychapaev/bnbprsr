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
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $title;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $description;

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
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private bool $processed;

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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $lastProcessAt;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private \DateTimeInterface $updatedAt;

    public function __construct(string $contract, string $status, ?string $title, ?string $description)
    {
        $this->contract = $contract;
        $this->status = $status;
        $this->lastTransactionHash = null;
        $this->lastProcessAt = null;
        $this->processed = false;
        $this->transactions = new ArrayCollection();
        $this->title = $title;
        $this->description = $description;
    }

    public function update(string $contract, string $status, ?string $title, ?string $description)
    {
        $this->contract = $contract;
        $this->status = $status;
        $this->title = $title;
        $this->description = $description;

    }

    public function updateLastTransactionHash(?string $hash): self
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

    public function getStatus()
    {
        return $this->status;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getProcessed() : bool
    {
        return $this->processed;
    }

    public function startProcess() : self
    {
        $this->processed = true;
        return $this;
    }

    public function finishProcess() : self
    {
        $this->processed = false;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

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

    public function getTransactions(): ArrayCollection
    {
        return $this->transactions;
    }

    public function updateLastProcessAt(){
        $this->lastProcessAt = new \DateTime();
        return $this;
    }
}