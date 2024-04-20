<?php

namespace App\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeTest extends WebTestCase
{
    // public function testApiDocUrlIsSuccessful(): void
    // {
    //     $client = self::createClient();
    //     // $client->followRedirects(false);
    //     $client->request('GET', '/api/doc');
    //     // $client->request('POST', '/api/doc');
    //     self::assertResponseIsSuccessful();
    // }

    // public function testApiAccountUrlIsSecure(): void
    // {
    //     $client = self::createClient();
    //     $client->followRedirects(false);
    //     $client->request('GET', '/api/account/me');
    //     self::assertResponseStatusCodeSame(401);
    // }

    public function testLoginRouteCanConnectAValidUser(): void
    {
        $client = self::createClient();
        $client->followRedirects(false);

        // $client->request('POST', '/api/registration', [], [], [
        //     'Content-type' => 'application/json'
        // ],json_encode([
        //     'firstName' => 'toto',
        //     'lastName' => 'toto',
        //     'email' => 'toto@toto.fr',
        //     'password' => 'toto',
        // ], JSON_THROW_ON_ERROR));

        $client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ],json_encode([
            'username' => 'toto@toto.fr',
            'password' => 'toto',
        ], JSON_THROW_ON_ERROR));

        // $statusCode = $client->getResponse();
        // dd($statusCode);
        $statusCode = $client->getResponse()->getStatusCode();
        $this->assertEquals(200, $statusCode);
        $content = $client->getResponse()->getContent();
        $this->assertStringContainsString('user', $content);
        dd($content);
    }
}