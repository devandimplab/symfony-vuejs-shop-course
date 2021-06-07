<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/admin")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="admin_security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        /*if ($this->getUser()) {
            return $this->redirectToRoute('main_profile_index');
        }*/

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="admin_security_logout")
     */
    public function logout()
    {
        return $this->redirectToRoute('admin_security_login');
    }
}
