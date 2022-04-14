<?php


namespace App\Model\Parser\UseCase\Cpanel\Task\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     */
    public ?string $contract;

    public bool $status;

    public ?string $title = null;

    public ?string $description = null;
}