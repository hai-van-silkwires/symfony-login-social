<?php


namespace App\Controller;


use App\Service\LinkedinService;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;

class LinkedinController
{
    /**
     * @var LinkedinService
     */
    private $linkedinService;

    public function __construct(
        LinkedinService $linkedinService
    ) {
        $this->linkedinService = $linkedinService;
    }

    public function login(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('linkedin')
            ->redirect([
                'r_liteprofile',
                'r_emailaddress',
            ]);
    }

    public function process(Request $request, ClientRegistry $clientRegistry)
    {
        $client = $clientRegistry->getClient('linkedin');

        try {
            $token = $client->getAccessToken();
            $accessToken = $token->getToken();
            $user = $client->fetchUserFromToken($token);

            if (is_null($user->getEmail())) {

            } else {
                $result = $this->linkedinService->process($user, $token->getToken());
            }
        } catch (IdentityProviderException $e) {
        }
    }
}