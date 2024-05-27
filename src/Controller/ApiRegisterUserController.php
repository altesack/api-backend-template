<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\CreateUserAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiRegisterUserController extends AbstractController
{

    #[Route('/api/register', name: 'app_api_register_user', methods: ['POST'])]
    public function index(CreateUserAction $createUserAction, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent());
            $createUserAction
                ->create(
                    $data->username,
                    $data->password,
                    [User::ROLE_USER],
                );

            return $this->json([]);
        } catch (\Throwable $th) {
            return $this->json([
                    'message' => 'User with such name already exists'
                ],
                400,
            );
        }
    }
}
