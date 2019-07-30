<?php

namespace App\Tests;
use Symfony\Component\Security\Core\Encoder\SodiumPasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testRegister()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/register',[],[],
        ['CONTENT_TYPE'=>"application/json"],
        '{
            "username":"malick",
            "password":"123",
            "nomComplet":"malick",
            "adresse":"mbour",
            "telephone":"2214478920",
            "email":"malick@malick.fr",
            "profil":"admin",
            "status":"debloquer"

        }'
    
    );
    $reponse=$client->getResponse();
        $this->assertSame(401,$reponse->getStatusCode());
    }

    public function testAjoutKo()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/register',[],[],
        ['CONTENT_TYPE'=>"application/json"],
        '{
            "username":"malick",
            "password":"123",
            "nomComplet":"malick",
            "adresse":"mbour",
            "telephone":"2214478920",
            "email":"malick@malick.fr",
            "profil":"admin",
            "status":1
        }');
        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(401,$client->getResponse()->getStatusCode());
    }
}
