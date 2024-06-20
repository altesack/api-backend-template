<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use App\Model\UserRegisterDTO;

class UserRegisterStateProcessor implements ProcessorInterface
{
    public function __construct(private UserHashPasswordStateProcessor $userHashPasswordStateProcessor)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        /** @var UserRegisterDTO @data */
        $user = (new User())
            ->setUsername($data->username)
            ->setPassword($data->password)
            ->setRoles([User::ROLE_USER])
        ;

        return $this->userHashPasswordStateProcessor->process($user, $operation, $uriVariables, $context);
    }
}
