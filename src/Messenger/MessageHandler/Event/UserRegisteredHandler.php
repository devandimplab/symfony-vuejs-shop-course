<?php

namespace App\Messenger\MessageHandler\Event;

use App\Entity\User;
use App\Messenger\Message\Event\UserRegisteredEvent;
use App\Security\Verifier\EmailVerifier;
use App\Utils\Mailer\Sender\UserRegisteredEmailSender;
use App\Utils\Manager\UserManager;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UserRegisteredHandler implements MessageHandlerInterface
{
    /**
     * @var EmailVerifier
     */
    private $emailVerifier;
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var UserRegisteredEmailSender
     */
    private $emailSender;

    public function __construct(EmailVerifier $emailVerifier, UserManager $userManager, UserRegisteredEmailSender $emailSender)
    {
        $this->emailVerifier = $emailVerifier;
        $this->userManager = $userManager;
        $this->emailSender = $emailSender;
    }

    public function __invoke(UserRegisteredEvent $event)
    {
        $userId = $event->getUserId();
        /** @var User $user */
        $user = $this->userManager->find($userId);

        if (!$user) {
            return;
        }

        $emailSignature = $this->emailVerifier->generateEmailSignature('main_verify_email', $user);
        $this->emailSender->sendEmailToClient($user, $emailSignature);
    }
}