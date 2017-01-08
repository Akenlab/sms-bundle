<?php

namespace Akenlab\SMSBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SMSControllerTest extends WebTestCase
{
    public function testSend()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'send');
    }

    public function testApicallback()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'APICallback');
    }

}
