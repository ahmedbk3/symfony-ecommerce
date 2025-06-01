<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeAdminCommand extends Command
{
    protected static $defaultName = 'app:make-admin';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Promotes a user to admin')
            ->addArgument('email', InputArgument::REQUIRED, 'Email of the user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            $output->writeln('<error>User not found.</error>');
            return Command::FAILURE;
        }

        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $this->entityManager->flush();

        $output->writeln('<info>User ' . $email . ' has been promoted to admin.</info>');
        return Command::SUCCESS;
    }
}
