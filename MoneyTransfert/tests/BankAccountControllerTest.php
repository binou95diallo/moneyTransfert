<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BankAccountControllerTest extends WebTestCase
{
    public function testAjout()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/bankAccount/ajout',[],[],
        ['CONTENT_TYPE'=>"application/json"],
        '{
            "numeroCompte":"ma01475",
            "solde":5000000,
            "partenaire":30

        }'
    
    );
    $reponse=$client->getResponse();
        $this->assertSame(401,$reponse->getStatusCode());
    }

    public function testAjoutKo()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/bankAccount/ajout',[],[],
        ['CONTENT_TYPE'=>"application/json"],
        '{
            "numeroCompte":"ma01475",
            "solde":5000000,
            "partenaire":"30"
        }');
        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(401,$client->getResponse()->getStatusCode());
    }

    public function testShow()
    {
        $client = static::viewClient();
        $crawler = $client->request('GET', '/api/bankAccount/1',[],[],
        );
        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(401,$client->getResponse()->getStatusCode());
    }
}
