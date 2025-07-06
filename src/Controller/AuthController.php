<?php

namespace App\Controller;

use App\Document\User; // ou App\Entity\User selon ton projet
use Doctrine\ODM\MongoDB\DocumentManager; // ou EntityManagerInterface pour Doctrine ORM
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        DocumentManager $dm,
        JWTTokenManagerInterface $jwtManager
    ): JsonResponse {
        try {
            $data = json_decode($request->getContent(), true);

            if (!$data || empty($data['username']) || empty($data['password'])) {
                return new JsonResponse(['message' => 'Tous les champs sont obligatoires.'], 400);
            }

            $user = $dm->getRepository(User::class)->findOneBy(['username' => $data['username']]);
            if (!$user) {
                return new JsonResponse(['message' => 'Utilisateur non trouvé.'], 404);
            }

            if (!$passwordHasher->isPasswordValid($user, $data['password'])) {
                return new JsonResponse(['message' => 'Mot de passe incorrect.'], 401);
            }

            // Génération du token JWT
            $token = $jwtManager->create($user);

            return new JsonResponse([
                'message' => 'Connexion réussie.',
                'token' => $token,
                'user' => [
                    'id' => $user->getId(),
                    'username' => $user->getUsername(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Erreur serveur : ' . $e->getMessage()], 500);
        }
    }
}
