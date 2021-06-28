<?php

namespace App\Tests\Functional\ApiPlatform;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Tests\TestUtils\Fixtures\UserFixtures;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group functional
 */
class ProductResourceTest extends ResourceTestUtils
{
    /**
     * @var string
     */
    protected $uriKey = '/api/products';

    public function testGetProducts()
    {
        $client = self::createClient();

        $client->request('GET', $this->uriKey, [], [], self::REQUEST_HEADERS);

        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetProduct()
    {
        $client = self::createClient();

        /** @var Product $product */
        $product = self::$container->get(ProductRepository::class)->findOneBy([]);

        $uri = $this->uriKey.'/'.$product->getUuid();

        $client->request('GET', $uri, [], [], self::REQUEST_HEADERS);

        $this->assertResponseStatusCodeSame(200);
    }

    public function testCreateProduct()
    {
        $client = self::createClient();

        $this->checkDefaultUserHasNotAccess($client, $this->uriKey, 'POST');

        $user = self::$container->get(UserRepository::class)->findOneBy(['email' => UserFixtures::USER_ADMIN_1_EMAIL]);

        $client->loginUser($user, 'main');

        $context = [
            'title' => 'New Product',
            'price' => '100',
            'quantity' => 5
        ];

        $client->request('POST', $this->uriKey, [], [], self::REQUEST_HEADERS, json_encode($context));

        $this->assertResponseStatusCodeSame(201);
    }

    public function testPatchProduct()
    {
        $client = self::createClient();

        /** @var Product $product */
        $product = self::$container->get(ProductRepository::class)->findOneBy([]);

        $uri = $this->uriKey.'/'.$product->getUuid();

        $this->checkDefaultUserHasNotAccess($client, $uri, 'PATCH');

        $user = self::$container->get(UserRepository::class)->findOneBy(['email' => UserFixtures::USER_ADMIN_1_EMAIL]);

        $client->loginUser($user, 'main');

        $context = [
            'title' => 'Updated Product',
        ];

        $client->request('PATCH', $uri, [], [], self::REQUEST_HEADERS_PATCH, json_encode($context));

        $this->assertResponseStatusCodeSame(200);
    }
}