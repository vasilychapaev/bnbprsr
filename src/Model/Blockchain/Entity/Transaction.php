<?php


namespace App\Model\Blockchain\Entity;

use App\Model\Parser\Entity\Process;
use App\Model\Parser\Entity\Task;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Model\Blockchain\Repository\TransactionRepository")
 * @ORM\Table(name="blockchain_transactions")
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Model\Parser\Entity\Task", inversedBy="transactions")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private Task $task;

    /**
     * @ORM\ManyToOne(targetEntity="App\Model\Parser\Entity\Process", inversedBy="transactions")
     * @ORM\JoinColumn(name="process_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private Process $process;

    /**
     * @ORM\Column(type="string")
     */
    private string $status;

    /**
     * @ORM\Column(type="string")
     */
    private string $hash;

    /**
     * @ORM\Column(type="string")
     */
    private string $method;

    /**
     * @ORM\Column(type="integer")
     */
    private string $block;

    /**
     * @ORM\Column(type="string", name="from_contract")
     */
    private string $from;

    /**
     * @ORM\Column(type="string", name="to_contract")
     */
    private string $to;

    /**
     * @ORM\Column(type="float")
     */
    private float $fee;

    /**
     * @ORM\Column(type="float")
     */
    private float $feeUSD;

    /**
     * @ORM\Column(type="float")
     */
    private float $value;

    /**
     * @ORM\Column(type="float")
     */
    private float $valueUSD;

    /**
     * @ORM\Column(type="string")
     */
    private string $currency;

    /**
     * @ORM\Column(type="text")
     */
    private string $detailRaw;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface  $dateTime;

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
        Process $process,
        string $hash,
        string $status,
        string$method,
        int $block,
        string $from,
        string $to,
        float $fee,
        float $feeUSD,
        float $value,
        float $valueUSD,
        string $currency,
        \DateTime $dateTime,
        $detailRaw
    )
    {
        $this->task = $task;
        $this->process = $process;
        $this->hash = $hash;
        $this->status = $status;
        $this->method = $method;
        $this->block = $block;
        $this->from = $from;
        $this->to = $to;
        $this->fee = $fee;
        $this->feeUSD = $feeUSD;
        $this->value = $value;
        $this->valueUSD = $valueUSD;
        $this->currency = $currency;
        $this->dateTime = $dateTime;
        $this->detailRaw = $detailRaw;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function getBlock(): ?int
    {
        return $this->block;
    }

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function getTo(): ?string
    {
        return $this->to;
    }

    public function getFee(): ?float
    {
        return $this->fee;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function getProcess(): ?Process
    {
        return $this->process;
    }

    public function getFeeUSD(): float
    {
        return $this->feeUSD;
    }

    public function getValueUSD(): float
    {
        return $this->valueUSD;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function setBlock(int $block): self
    {
        $this->block = $block;

        return $this;
    }

    public function setFrom(string $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function setTo(string $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function setFee(float $fee): self
    {
        $this->fee = $fee;

        return $this;
    }

    public function setFeeUSD(float $feeUSD): self
    {
        $this->feeUSD = $feeUSD;

        return $this;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function setValueUSD(float $valueUSD): self
    {
        $this->valueUSD = $valueUSD;

        return $this;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function setDateTime(\DateTimeInterface $dateTime): self
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function setProcess(?Process $process): self
    {
        $this->process = $process;

        return $this;
    }
}