<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

class GetUser
{
    public function __construct(
        private Security $security,
    ){

    }

    public function getUser(): User
    {
        return $user = $this->security->getUser();
    }

}