<?php


namespace App\Model\Parser\UseCase\Cpanel\Task\Edit;

use App\Model\Parser\Entity\Task;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    public int $id;

    /**
     * @Assert\NotBlank()
     */
    public ?string $contract;

    public bool $status;

    public ?string $title;

    public ?string $description;

    public function __construct(Task $task)
    {
        $this->id = $task->getId();
        $this->title = $task->getTitle();
        $this->description = $task->getDescription();
        $this->contract = $task->getContract();
        $this->status = $task->getStatus();
    }
}