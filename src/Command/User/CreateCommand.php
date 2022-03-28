<?php

namespace App\Command\User;

use App\Model\Flusher;
use App\Model\User\Entity\User;
use App\Model\User\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateCommand extends Command
{
    protected static $defaultName = 'app:user:create';
    protected static $defaultDescription = 'Add new user';

    private UserRepository $repository;

    private UserPasswordHasherInterface $hasher;

    private Flusher $flusher;

    public function __construct(UserRepository $repository,
                                UserPasswordHasherInterface $hasher,
                                Flusher $flusher,
                                string $name = null)
    {
        parent::__construct($name);
        $this->repository = $repository;
        $this->hasher = $hasher;
        $this->flusher = $flusher;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Argument username')
            ->addArgument('email', InputArgument::REQUIRED, 'Argument email')
            ->addArgument('password', InputArgument::REQUIRED, 'Argument password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);


        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        if (!$username) {
            $io->error('Enter username');
            return Command::FAILURE;
        }

        if (!$email) {
            $io->error('Enter email');
            return Command::FAILURE;
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $io->error('Email address not valid');
            return Command::FAILURE;
        }

        if (!$password) {
            $io->error('Enter password');
            return Command::FAILURE;
        }

        $io->note(sprintf('You passed an username: %s', $username));
        $io->note(sprintf('You passed an email: %s', $email));
        $io->note(sprintf('You passed an password: %s', $password));

        $user = new User($username, $email);

        $password = $this->hasher->hashPassword($user, $password);

        $user->changePassword($password);

        $this->repository->add($user);

        $this->flusher->flush();

        $io->success('Success, user created');

        return Command::SUCCESS;
    }
}
