<?php

namespace App\Utils\ApiPlatform\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Cart;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class FilterCartQueryExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Security $security, RequestStack $request)
    {
        $this->security = $security;
        $this->request = $request;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        $this->andWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        $this->andWhere($queryBuilder, $resourceClass);
    }

    private function andWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        if (Cart::class !== $resourceClass) {
            return;
        }

        $user = $this->security->getUser();

        /**
         * This is just an example of a check.
         * If your project doesn't need this check, just remove the method and this check.
         */
        if ($this->displayAllForAdmin($user)) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];

        $request = Request::createFromGlobals();
        $phpSessId = $request->cookies->get("PHPSESSID");

        $queryBuilder->andWhere(
            sprintf("%s.sessionId = '%s'", $rootAlias, $phpSessId)
        );
    }

    /**
     * If you want to show all carts in the admin section (only for admin)
     * Add query param "context = admin"
     *
     * Ex.: https://127.0.0.1:8000/api/carts?page=1&context=admin
     *
     * @param UserInterface $user
     * @return bool
     */
    private function displayAllForAdmin(UserInterface $user): bool
    {
        return (
            $user instanceof User
            && $user->isAdminRole()
            && $this->request->getCurrentRequest()->get('context') === 'admin'
        );
    }
}