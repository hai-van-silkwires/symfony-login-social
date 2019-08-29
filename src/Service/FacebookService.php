<?php


namespace App\Service;

use App\Entity\Facebook;
use App\Repository\FacebookRepository;
use App\Repository\UserRepository;

class FacebookService
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var FacebookRepository
     */
    private $facebookRepository;

    public function __construct(
        UserRepository $userRepository,
        FacebookRepository $facebookRepository
    ) {
        $this->userRepository = $userRepository;
        $this->facebookRepository = $facebookRepository;
    }

    public function process($user, $accessToken)
    {

    }

    public function create($data)
    {
        //@TODO: Create new facebook account
    }
}