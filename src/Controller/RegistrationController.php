<?php

namespace App\Controller;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        DocumentManager $dm,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $username = $data['username'] ?? null;
        $plainPassword = $data['password'] ?? null;
        $age = $data['age'] ?? null;
        $poids = $data['poids'] ?? null;

        if (!$username || !$plainPassword || !$age || !$poids) {
            return new JsonResponse(['message' => 'Champs manquants'], Response::HTTP_BAD_REQUEST);
        }

        // Vérifie si l'utilisateur existe déjà
        $existingUser = $dm->getRepository(User::class)->findOneBy(['username' => $username]);
        if ($existingUser) {
            return new JsonResponse(['message' => 'Utilisateur déjà existant'], Response::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
        $user->setAge((int)$age);
        $user->setPoids((float)$poids);
        $user->setRoles(['ROLE_USER']);

        $dm->persist($user);
        $dm->flush();

        return new JsonResponse(['message' => 'Utilisateur inscrit avec succès'], Response::HTTP_CREATED);
    }
}
