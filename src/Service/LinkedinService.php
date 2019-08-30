<?php


namespace App\Service;

use App\Repository\UserRepository;

class LinkedinService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }
}