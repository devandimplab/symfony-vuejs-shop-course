<?php

namespace App\EventSubscriber;

use App\Event\OrderCreatedFromCartEvent;
use App\Utils\Mailer\Sender\OrderCreatedFromCartEmailSender;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OrderCreatedFromCartSendNotificationSubscriber implements EventSubscriberInterface
{
    /**
     * @var OrderCreatedFromCartEmailSender
     */
    private $orderCreatedFromCartEmailSender;

    public function __construct(OrderCreatedFromCartEmailSender $orderCreatedFromCartEmailSender)
    {
        $this->orderCreatedFromCartEmailSender = $orderCreatedFromCartEmailSender;
    }

    public function onOrderCreatedFromCartEvent(OrderCreatedFromCartEvent $event)
    {
        $order = $event->getOrder();

        $this->orderCreatedFromCartEmailSender->sendEmailToClient($order);
        $this->orderCreatedFromCartEmailSender->sendEmailToManager($order);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderCreatedFromCartEvent::class => 'onOrderCreatedFromCartEvent',
        ];
    }
}
