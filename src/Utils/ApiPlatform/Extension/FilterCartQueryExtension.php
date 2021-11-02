<?php

namespace App\Utils\ApiPlatform\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class FilterCartQueryExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
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

        if ($user instanceof User && $user->isAdminRole()) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];

        $request = Request::createFromGlobals();
        $phpSessId = $request->cookies->get("PHPSESSID");

        $queryBuilder->andWhere(
            sprintf("%s.sessionId = '%s'", $rootAlias, $phpSessId)
        );
    }
}