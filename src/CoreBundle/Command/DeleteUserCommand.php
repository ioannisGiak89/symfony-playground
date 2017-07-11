<?php

namespace CoreBundle\Command;

use CoreBundle\Repository\UserRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * A command for deleting a user
 *
 * @author Ioannis Giakoumidis <ioannis.giakoumidis@inviqa.com>
 */
class DeleteUserCommand extends Command
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * DeleteUserCommand constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        parent::__construct("core:user:delete");
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setDescription("Deletes a user");
        $this->addOption(
            "id",
            "i",
            InputOption::VALUE_REQUIRED,
            "Id of the user in database"
            )
            ->addOption(
                "email",
                "E",
                InputOption::VALUE_REQUIRED,
                "Email of the user"
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     *
     * @throws EntityNotFoundException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $options = $input->getOptions();
        if ($options["id"]) {
            $user = $this->userRepository->findOneById($options["id"]);

            if (!$user) {
                throw new EntityNotFoundException(
                    'No user found for id ' . $options["id"]
                );
            }
            $this->userRepository->deleteUser($user);
            $output->writeln("User has been successfully deleted");
            return;
        }

        if ($options["email"]) {
            $user = $this->userRepository->findOneByEmail($options["email"]);

            if (!$user) {
                throw new EntityNotFoundException(
                    'No user found for email ' . $options["email"]
                );
            }

            $this->userRepository->deleteUser($user);
            $output->writeln("User has been successfully deleted");
            return;
        }

        $output->writeln("<error>You need to provide an Id or an email. Check core:user:delete --help</error>");
    }


}
