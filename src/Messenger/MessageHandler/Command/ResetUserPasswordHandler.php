<?php

namespace App\Messenger\MessageHandler\Command;

use App\Entity\User;
use App\Messenger\Message\Command\ResetUserPassword;
use App\Utils\Mailer\Sender\ResetUserPasswordEmailSender;
use App\Utils\Manager\UserManager;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class ResetUserPasswordHandler implements MessageHandlerInterface
{
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var ResetPasswordHelperInterface
     */
    private $resetPasswordHelper;
    /**
     * @var ResetUserPasswordEmailSender
     */
    private $emailSender;

    public function __construct(UserManager $userManager, ResetPasswordHelperInterface $resetPasswordHelper, ResetUserPasswordEmailSender $emailSender)
    {

        $this->userManager = $userManager;
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->emailSender = $emailSender;
    }

    public function __invoke(ResetUserPassword $resetUserPassword)
    {
        $email = $resetUserPassword->getEmail();
        $resetToken = null;

        /** @var User $user */
        $user = $this->userManager->getRepository()->findOneBy([
            'email' => $email
        ]);

        if (!$user) {
            return;
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
            $this->emailSender->sendEmailToClient($user, $resetToken);
        } catch (ResetPasswordExceptionInterface $exception) {
            //
        }
    }
}