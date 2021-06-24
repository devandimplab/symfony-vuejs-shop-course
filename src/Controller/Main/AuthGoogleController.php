<?php

namespace App\Controller\Main;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthGoogleController extends AbstractController
{
    /**
     * @Route("/connect/google", name="connect_google_start")
     */
    public function connectAction(ClientRegistry $clientRegistry): Response
    {
        return $clientRegistry
            ->getClient('google_main')
            ->redirect([], []);
    }

    /**
     * @Route("/connect/google/check", name="connect_google_check")
     */
    public function connectCheckAction()
    {
        //
    }
}
