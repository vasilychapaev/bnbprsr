<?php

namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;

class Flusher
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function flush(): void
    {
        $this->manager->flush();
    }
}