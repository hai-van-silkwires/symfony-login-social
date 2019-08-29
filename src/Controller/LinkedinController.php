<?php


namespace App\Controller;


use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;

class LinkedinController
{
    public function login(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('linkedin')
            ->redirect([
                'r_liteprofile',
                'r_emailaddress',
            ])
            ;
    }

    public function process(Request $request, ClientRegistry $clientRegistry)
    {
        $client = $clientRegistry->getClient('linkedin');

        try {
            $user = $client->fetchUser();
        } catch (IdentityProviderException $e) {
        }
    }
}