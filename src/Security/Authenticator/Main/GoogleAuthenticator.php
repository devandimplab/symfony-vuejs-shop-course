<?php

namespace App\Security\Authenticator\Main;

use App\Entity\User;
use App\Event\UserLoggedInViaSocialNetworkEvent;
use App\Utils\Factory\UserFactory;
use App\Utils\Generator\PasswordGenerator;
use App\Utils\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class GoogleAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;

    private $router;
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(ClientRegistry $clientRegistry, UserManager $userManager, RouterInterface $router, SessionInterface $session, EventDispatcherInterface $eventDispatcher)
    {
        $this->clientRegistry = $clientRegistry;
        $this->router = $router;
        $this->userManager = $userManager;
        $this->session = $session;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function supports(Request $request)
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    public function getCredentials(Request $request)
    {
        // this method is only called if supports() returns true

        // For Symfony lower than 3.4 the supports method need to be called manually here:
        // if (!$this->supports($request)) {
        //     return null;
        // }

        return $this->fetchAccessToken($this->getGoogleClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var GoogleUser $googleUser */
        $googleUser = $this->getGoogleClient()
            ->fetchUserFromToken($credentials);

        $email = $googleUser->getEmail();

        // 1) have they logged in with Facebook before? Easy!
        $existingUser = $this->userManager->getRepository()->findOneBy(['googleId' => $googleUser->getId()]);

        if ($existingUser) {
            return $existingUser;
        }

        // 2) do we have a matching user by email?
        $user = $this->userManager->getRepository()->findOneBy(['email' => $email]);

        if (!$user) {
            $user = UserFactory::createUserFromGoogleUser($googleUser);

            $plainPassword = PasswordGenerator::generatePassword();
            $this->userManager->encodePassword($user, $plainPassword);

            $event = new UserLoggedInViaSocialNetworkEvent($user, $plainPassword);
            $this->eventDispatcher->dispatch($event);

            $this->session->getFlashBag()->add('success', 'An email has been sent. Please check your inbox to find password');
            $this->userManager->save($user);
        }

        // 3) Maybe you just want to "register" them by creating
        // a User object
        $user->setGoogleId($googleUser->getId());
        $this->userManager->save($user);

        return $user;
    }

    /**
     * @return GoogleClient
     */
    private function getGoogleClient()
    {
        return $this->clientRegistry
            // "google_main" is the key used in config/packages/knpu_oauth2_client.yaml
            ->getClient('google_main');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // change "app_homepage" to some route in your app
        $targetUrl = $this->router->generate('main_profile_index');

        return new RedirectResponse($targetUrl);

        // or, on success, let the request continue to be handled by the controller
        //return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            '/connect/', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    // ...
}