<?php


namespace App\Model\Parser\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Model\Parser\Repository\ProcessRepository")
 * @ORM\Table(name="parser_processes")
 */
class Process
{
    const STATUS_WAIT = 'wait';

    const STATUS_PROCESSED = 'processed';

    const STATUS_FINISH = 'finish';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="processes")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private Task $task;

    /**
     * @ORM\OneToMany(targetEntity="App\Model\Blockchain\Entity\Transaction", mappedBy="process")
     */
    private Collection $transactions;

    /**
     * @ORM\Column(type="string")
     */
    private string $status;

    /**
     * @ORM\Column(type="integer")
     */
    private int $processed;

    /**
     * @ORM\Column(type="integer")
     */
    private int $failure;

    /**
     * @ORM\Column(type="integer")
     */
    private int $total;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private string $log;

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

    public function __construct(
        Task $task,
        int $total

    )
    {
        $this->task = $task;
        $this->failure = 0;
        $this->processed = 0;
        $this->total = $total;
        $this->status = self::STATUS_WAIT;
        $this->transactions = new ArrayCollection();
    }

    public function updateStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function incrementProcessed(int $value = 1){
        $this->processed += $value;
    }

    public function incrementFailure(int $value = 1){
        $this->failure += $value;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getProcessed(): int
    {
        return $this->processed;
    }

    public function getFailure(): int
    {
        return $this->failure;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getLog(): string
    {
        return $this->log;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function getTransactions(): ArrayCollection
    {
        return $this->transactions;
    }
}