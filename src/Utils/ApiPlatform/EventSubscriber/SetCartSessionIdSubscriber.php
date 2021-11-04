<?php

namespace App\Utils\ApiPlatform\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Cart;
use App\Utils\Generator\TokenGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SetCartSessionIdSubscriber implements EventSubscriberInterface
{
    public function setSessionId(ViewEvent $event)
    {
        $cart = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$cart instanceof Cart || Request::METHOD_POST !== $method) {
            return;
        }

        $cartToken = $event->getRequest()->cookies->get('CART_TOKEN');

        if (!$cartToken) {
            $cartToken = TokenGenerator::generateToken();
        }

        $cart->setToken($cartToken);
        dd($event->getResponse(), $cart);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                'setSessionId', EventPriorities::PRE_WRITE
            ]
        ];
    }
}