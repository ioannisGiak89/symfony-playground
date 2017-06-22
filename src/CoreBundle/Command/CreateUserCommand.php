<?php

namespace CoreBundle\Command;

use CoreBundle\Entity\User;
use CoreBundle\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * A command to create a user
 *
 * @author Ioannis Giakoumidis <ioannis.giakoumidis@inviqa.com>
 */
class CreateUserCommand extends Command
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * CreateUserCommand constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        parent::__construct("core:user:create");
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setDescription("Creates a user");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("You are about to create a user");

        $io = new SymfonyStyle($input, $output);
        $username = $io->ask("Username?",  null, function ($username) {
            if (!is_string($username)) {
                throw new \RuntimeException("You must type a name.");
            }

            return $username;
        });

        $email = $io->ask("Email?",  null, function ($email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException("Enter a valid email");
            }

            return $email;
        });

        $password = $io->askHidden("Password?", function ($password) {
            if (!$password) {
                throw new \RuntimeException("Enter a password");
            }

            return $password;
        });

        $mobile = $io->ask("Mobile (Optional)?",  null, function ($mobile) {
            return $mobile;
        });

        $user = new User();
        $user->setusername($username);
        $user->setEmail($email);
        $user->setPlainPassword($password);

        if ($mobile) {
            $user->setMobile($mobile);
        }

        $this->userRepository->save($user);
        $output->writeln("User has been successfully created");
    }
}
