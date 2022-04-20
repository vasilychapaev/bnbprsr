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
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private ?Task $task;

    /**
     * @ORM\ManyToOne(targetEntity="App\Model\Parser\Entity\Process", inversedBy="transactions")
     * @ORM\JoinColumn(name="process_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private ?Process $process;

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
     * @ORM\Column(type="string", name="from_wallet")
     */
    private string $fromWallet;

    /**
     * @ORM\Column(type="string", name="to_wallet1")
     */
    private string $toWallet1;

    /**
     * @ORM\Column(type="string", name="to_wallet2", nullable=true)
     */
    private ?string $toWallet2;

    /**
     * @ORM\Column(type="string", name="to_wallet3", nullable=true)
     */
    private ?string $toWallet3;

    /**
     * @ORM\Column(type="string", name="to_wallet4", nullable=true)
     */
    private ?string $toWallet4;

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
    private float $value1;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $value2;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $value3;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $value4;

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
     * @ORM\Column(type="json")
     */
    private array $toWalletJson;

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
        string $fromWallet,
        string $toWallet1,
        ?string $toWallet2,
        ?string $toWallet3,
        ?string $toWallet4,
        float $fee,
        float $feeUSD,
        float $value1,
        ?float $value2,
        ?float $value3,
        ?float $value4,
        float $valueUSD,
        string $currency,
        array $toWalletJson,
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
        $this->fromWallet = $fromWallet;
        $this->toWallet1 = $toWallet1;
        $this->toWallet2 = $toWallet2;
        $this->toWallet3 = $toWallet3;
        $this->toWallet4 = $toWallet4;
        $this->fee = $fee;
        $this->feeUSD = $feeUSD;
        $this->value1 = $value1;
        $this->value2 = $value2;
        $this->value3 = $value3;
        $this->value4 = $value4;
        $this->valueUSD = $valueUSD;
        $this->currency = $currency;
        $this->dateTime = $dateTime;
        $this->detailRaw = $detailRaw;
        $this->toWalletJson = $toWalletJson;
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

    public function getFromWallet(): ?string
    {
        return $this->fromWallet;
    }

    public function getToWallet1(): ?string
    {
        return $this->toWallet1;
    }

    public function getFee(): ?float
    {
        return $this->fee;
    }

    public function getValue1(): ?float
    {
        return $this->value1;
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