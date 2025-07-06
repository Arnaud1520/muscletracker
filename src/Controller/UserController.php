<?php

namespace App\Controller;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/users')]
class UserController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(DocumentManager $dm): JsonResponse
    {
        $users = $dm->getRepository(User::class)->findAll();
        $data = array_map(fn(User $user) => [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'age' => $user->getAge(),
            'poids' => $user->getPoids(),
        ], $users);

        return new JsonResponse($data);
    }

    #[Route('/me', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getCurrentUser(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['error' => 'Not authenticated'], 401);
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'age' => $user->getAge(),
            'poids' => $user->getPoids(),
        ]);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function getUserById(string $id, DocumentManager $dm): JsonResponse
    {
        $user = $dm->getRepository(User::class)->find($id);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'age' => $user->getAge(),
            'poids' => $user->getPoids(),
        ]);
    }

    #[Route('', methods: ['POST'])]
    public function createUser(Request $request, DocumentManager $dm): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setUsername($data['username'] ?? '');
        $user->setAge((int)($data['age'] ?? 0));
        $user->setPoids((float)($data['poids'] ?? 0));

        $dm->persist($user);
        $dm->flush();

        return new JsonResponse(['message' => 'User created', 'id' => $user->getId()], 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function updateUser(string $id, Request $request, DocumentManager $dm): JsonResponse
    {
        $user = $dm->getRepository(User::class)->find($id);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['username'])) {
            $user->setUsername($data['username']);
        }
        if (isset($data['age'])) {
            $user->setAge((int)$data['age']);
        }
        if (isset($data['poids'])) {
            $user->setPoids((float)$data['poids']);
        }

        $dm->flush();

        return new JsonResponse(['message' => 'User updated']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function deleteUser(string $id, DocumentManager $dm): JsonResponse
    {
        $user = $dm->getRepository(User::class)->find($id);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        $dm->remove($user);
        $dm->flush();

        return new JsonResponse(['message' => 'User deleted']);
    }
}
