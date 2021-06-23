<?php

namespace App\Utils\Mailer\Sender;

use App\Entity\Order;
use App\Utils\Mailer\DTO\MailerOptions;
use App\Utils\Mailer\MailerSender;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OrderCreatedFromCartEmailSender
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

    public function sendEmailToClient(Order $order)
    {
        $mailerOptions = (new MailerOptions())
            ->setRecipient($order->getOwner()->getEmail())
            ->setCc('manager@ranked-choice.com')
            ->setSubject('Ranked Choice Shop - Thank you for your purchase!')
            ->setHtmlTemplate('main/email/client/created_order_from_cart.html.twig')
            ->setContext([
                'order' => $order,
                'profileUrl' => $this->urlGenerator->generate('main_profile_index', [], UrlGeneratorInterface::ABSOLUTE_URL)
            ]);

        $this->mailerSender->sendTemplatedEmail($mailerOptions);
    }

    public function sendEmailToManager(Order $order)
    {
        $mailerOptions = (new MailerOptions())
            ->setRecipient('manager@ranked-choice.com')
            ->setSubject('Client created order')
            ->setHtmlTemplate('main/email/manager/created_order_from_cart.html.twig')
            ->setContext([
                'order' => $order
            ]);

        $this->mailerSender->sendTemplatedEmail($mailerOptions);
    }
}