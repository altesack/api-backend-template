<?php

namespace App\Command\Security;

use App\Entity\User;
use App\Service\UserCreator;
use App\Services\CreateUserAction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'security:create-admin',
    description: 'Add a short description for your command',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private CreateUserAction $createUserAction, 
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
     
        $passwordQuestion = (new Question('Type in your password to be hashed'))
            ->setHidden(true)
            ->setMaxAttempts(20)
        ;
        $password = $io->askQuestion($passwordQuestion);
        

        try {
            $this->createUserAction->create('admin', $password, [User::ROLE_USER, User::ROLE_ADMIN]);
                        
            $io->success('Admin is created');
    
            return Command::SUCCESS;

        } catch (\Throwable $th) {
            $io->error('Admin already exists');
    
            return Command::FAILURE;
        }
    }
}
