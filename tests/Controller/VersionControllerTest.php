<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class VersionControllerTest extends WebTestCase
{
    public function testGetErrorWhenSomethingIsWrong(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/version/fail');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testGetRedirectWhenVersionIsFound(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/version/6.4.16.0');

        $this->assertResponseRedirects('https://releases.shopware.com/sw6/install_v6.4.16.0_ccfc52c31c489bed8041a13e5725183575f0593b.zip');
    }

    public function testGetLatestPatchVersion(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/version/6.4.15');

        $this->assertResponseRedirects('https://releases.shopware.com/sw6/install_v6.4.15.2_51514a17f5a60aaa2e047c92e912ed5c083e965b.zip');
    }
}