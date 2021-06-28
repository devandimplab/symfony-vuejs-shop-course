<?php

namespace App\Tests\Functional\Controller\Main;

use App\Tests\Functional\SymfonyPanther\BasePantherTestCase;
use App\Tests\TestUtils\Fixtures\UserFixtures;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Panther\Client;

class AuthLoginControllerTest extends BasePantherTestCase
{
    /**
     * @group functional
     */
    public function testLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/en/login');
        $client->submitForm('LOG IN', [
            'email' => UserFixtures::USER_1_EMAIL,
            'password' => UserFixtures::USER_1_PASSWORD
        ]);

        $this->assertResponseRedirects('/en/profile', Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
    }

    /**
     * @group functional-selenium
     */
    public function testLoginWithSeleniumClient(): void
    {
        $client = $this->initSeleniumClient();

        $client->request('GET', '/en/login');
        $crawler = $client->submitForm('LOG IN', [
            'email' => UserFixtures::USER_1_EMAIL,
            'password' => UserFixtures::USER_1_PASSWORD
        ]);

        sleep(3);
        $this->takeScreenshot($client, 'auth-login-controller-test-login__1');

        $this->assertSame(
            $crawler->filter('#page_header_title')->text(),
            'Welcome, to your profile!'
        );
    }

    /**
     * @group functional-panther
     */
    public function testLoginWithPantherClient(): void
    {
        $client = self::createPantherClient(['browser' => self::CHROME]);

        $client->request('GET', '/en/login');
        $client->submitForm('LOG IN', [
            'email' => UserFixtures::USER_1_EMAIL,
            'password' => UserFixtures::USER_1_PASSWORD
        ]);

        $this->assertSame(self::$baseUri.'/en/profile', $client->getCurrentURL());

        $this->assertPageTitleContains('My profile - RankedChoice');
        $this->assertSelectorTextContains('#page_header_title', 'Welcome, to your profile!');
    }
}