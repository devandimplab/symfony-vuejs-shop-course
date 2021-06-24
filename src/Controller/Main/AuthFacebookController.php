<?php

namespace App\Controller\Main;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthFacebookController extends AbstractController
{
    /**
     * @Route("/connect/facebook", name="connect_facebook_start")
     */
    public function connectAction(ClientRegistry $clientRegistry): Response
    {
        return $clientRegistry
            ->getClient('facebook_main')
            ->redirect([], []);
    }

    /**
     * @Route("/connect/facebook/check", name="connect_facebook_check")
     */
    public function connectCheckAction()
    {
        //
    }
}
