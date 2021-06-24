<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class AddUserCommand extends Command
{
    protected static $defaultName = 'app:add-user';
    protected static $defaultDescription = 'Create user';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(string $name = null, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addOption('email', 'em', InputArgument::OPTIONAL, 'Email')
            ->addOption('password', 'p', InputArgument::OPTIONAL, 'Password')
            ->addOption('role', '', InputArgument::OPTIONAL, 'Set role')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $stopwatch = new Stopwatch();
        $stopwatch->start('add-user-command');

        $email = $input->getOption('email');
        $password = $input->getOption('password');
        $role = $input->getOption('role');

        $io->title('Add User Command Wizard');
        $io->text([
            'Please, enter some information'
        ]);

        if (!$email) {
            $email = $io->ask('Email');
        }

        if (!$password) {
            $password = $io->askHidden('Password (your type will be hidden)');
        }

        if (!$role) {
            $role = $io->ask('Set role');
        }

        try {
            $user = $this->createUser($email, $password, $role);
        } catch (RuntimeException $exception) {
            $io->comment($exception->getMessage());

            return Command::FAILURE;
        }

        $successMessage = sprintf('%s was successfully created: %s',
            $role,
            $email
        );
        $io->success($successMessage);

        $event = $stopwatch->stop('add-user-command');
        $stopwatchMessage = sprintf('New user\'s id: %s / Elapsed time: %.2f ms / Consumed memory: %.2f MB',
            $user->getId(),
            $event->getDuration(),
            $event->getMemory() / 1000 / 1000
        );
        $io->comment($stopwatchMessage);

        return Command::SUCCESS;
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $role
     * @return User
     */
    private function createUser(string $email, string $password, string $role): User
    {
        $existingUser = $this->userRepository->findOneBy(['email' => $email]);

        if ($existingUser) {
            throw new RuntimeException('User already exist');
        }

        $user = new User();
        $user->setEmail($email);
        $user->setRoles([$role]);

        $encodedPassword = $this->encoder->encodePassword($user, $password);
        $user->setPassword($encodedPassword);

        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
