<?php

namespace App\Utils\Mailer\Sender;

use App\Entity\User;
use App\Utils\Mailer\DTO\MailerOptions;
use App\Utils\Mailer\MailerSender;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserLoggedInViaSocialNetworkEmailSender
{
    /**
     * @var MailerSender
     */
    private $mailerSender;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(MailerSender $mailerSender, UrlGeneratorInterface $urlGenerator)
    {
        $this->mailerSender = $mailerSender;
        $this->urlGenerator = $urlGenerator;
    }

    public function sendEmailToClient(User $user, string $plainPassword)
    {
        $mailerOptions = (new MailerOptions())
            ->setRecipient($user->getEmail())
            ->setSubject('Ranked Choice Shop - Your new password')
            ->setHtmlTemplate('main/email/client/user_logged_in_via_social_network.html.twig')
            ->setContext([
                'user' => $user,
                'plainPassword' => $plainPassword,
                'profileUrl' => $this->urlGenerator->generate('main_profile_index', [], UrlGeneratorInterface::ABSOLUTE_URL)
            ]);

        $this->mailerSender->sendTemplatedEmail($mailerOptions);
    }
}