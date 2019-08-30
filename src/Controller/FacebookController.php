<?php


namespace App\Controller;

use App\Service\FacebookService;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\Facebook;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FacebookController extends AbstractController
{
    /**
     * @var FacebookService
     */
    private $facebookService;

    public function __construct(
        FacebookService $facebookService
    ) {
        $this->facebookService = $facebookService;
    }

    /**
     * @param ClientRegistry $clientRegistry
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function login(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('facebook')
            ->redirect([
                'email'
            ]);
    }

    /**
     * @param Request $request
     * @param ClientRegistry $clientRegistry
     */
    public function process(ClientRegistry $clientRegistry)
    {
        try {
            $client = $clientRegistry->getClient('facebook');
            $accessToken = $client->getAccessToken();
            $user = $client->fetchUserFromToken($accessToken);

            if (is_null($user->getEmail())) {
                // @TODO: Require input email if user's email is private
            } else {
                $result = $this->facebookService->process($user, $accessToken);
            }
        } catch (IdentityProviderException $e) {
            // @TODO: Process when has exception
        }
    }
}