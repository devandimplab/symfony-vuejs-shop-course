<?php

namespace App\Security\Voters;

use App\Entity\Cart;
use App\Entity\User;
use App\Repository\CartRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class CartVoter extends Voter
{
    private const CART_READ = 'CART_READ';
    private const CART_EDIT = 'CART_EDIT';
    private const CART_DELETE = 'CART_DELETE';

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports(string $attribute, $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::CART_READ, self::CART_EDIT, self::CART_DELETE])) {
            return false;
        }

        // only vote on `Cart` objects
        if (!$subject instanceof Cart) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if ($user instanceof User && $user->isAdminRole()) {
            return true;
        }

        // you know $subject is a Post object, thanks to `supports()`
        /** @var Cart $cart */
        $cart = $subject;

        switch ($attribute) {
            case self::CART_READ:
                return $this->canRead();
            case self::CART_EDIT:
                return $this->canEdit($cart);
            case self::CART_DELETE:
                return $this->canDelete($cart);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canRead(): bool
    {
        // так как отрабатывает проверка в FilterCartQueryExtension.php
        return true;
    }

    /**
     * @param Cart $cart
     * @return bool
     */
    private function canEdit(Cart $cart): bool
    {
        $phpSessionId = $this->getPhpSessionId();

        if (!$phpSessionId) {
            return false;
        }

        // если корзина еще не существует
        if (!$cart->getId()) {
            return true;
        }

        // проверяем, что это корзина пользователя
        return $cart->getSessionId() === $phpSessionId;
    }

    /**
     * @param Cart $cart
     * @return bool
     */
    private function canDelete(Cart $cart): bool
    {
        $phpSessionId = $this->getPhpSessionId();

        if (!$phpSessionId || !$cart->getId()) {
            return false;
        }

        // проверяем, что это корзина пользователя
        return $cart->getSessionId() === $phpSessionId;
    }

    /**
     * @return string|null
     */
    private function getPhpSessionId(): ?string
    {
        return $this->requestStack
            ->getCurrentRequest()
            ->cookies
            ->get('PHPSESSID');
    }
}
