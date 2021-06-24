<?php

namespace App\EventSubscriber;

use App\Event\UserLoggedInViaSocialNetworkEvent;
use App\Utils\Mailer\Sender\UserLoggedInViaSocialNetworkEmailSender;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserLoggedInViaSocialNetworkSendNotificationSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserLoggedInViaSocialNetworkEmailSender
     */
    private $emailSender;

    public function __construct(UserLoggedInViaSocialNetworkEmailSender $emailSender)
    {
        $this->emailSender = $emailSender;
    }

    public function onUserLoggedInViaSocialNetworkEvent(UserLoggedInViaSocialNetworkEvent $event)
    {
        $user = $event->getUser();
        $plainPassword = $event->getPlainPassword();

        $this->emailSender->sendEmailToClient($user, $plainPassword);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserLoggedInViaSocialNetworkEvent::class => 'onUserLoggedInViaSocialNetworkEvent'
        ];
    }
}