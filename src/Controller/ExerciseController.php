<?php

namespace App\Controller;

use App\Document\Exercise;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/exercises')]
class ExerciseController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(DocumentManager $dm): JsonResponse
    {
        $exercises = $dm->getRepository(Exercise::class)->findAll();
        $data = array_map(fn(Exercise $exercise) => [
            'id' => $exercise->getId(),
            'nom' => $exercise->getNom(),
            'categorie' => $exercise->getCategorie(),
            'description' => $exercise->getDescription(),
        ], $exercises);

        return new JsonResponse($data);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function getExercise(string $id, DocumentManager $dm): JsonResponse
    {
        $exercise = $dm->getRepository(Exercise::class)->find($id);
        if (!$exercise) {
            return new JsonResponse(['error' => 'Exercise not found'], 404);
        }

        return new JsonResponse([
            'id' => $exercise->getId(),
            'nom' => $exercise->getNom(),
            'categorie' => $exercise->getCategorie(),
            'description' => $exercise->getDescription(),
        ]);
    }

    #[Route('', methods: ['POST'])]
    public function createExercise(Request $request, DocumentManager $dm): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $exercise = new Exercise();
        $exercise->setNom($data['nom'] ?? '');
        $exercise->setCategorie($data['categorie'] ?? '');
        $exercise->setDescription($data['description'] ?? null);

        $dm->persist($exercise);
        $dm->flush();

        return new JsonResponse(['message' => 'Exercise created', 'id' => $exercise->getId()], 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function updateExercise(string $id, Request $request, DocumentManager $dm): JsonResponse
    {
        $exercise = $dm->getRepository(Exercise::class)->find($id);
        if (!$exercise) {
            return new JsonResponse(['error' => 'Exercise not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['nom'])) {
            $exercise->setNom($data['nom']);
        }
        if (isset($data['categorie'])) {
            $exercise->setCategorie($data['categorie']);
        }
        if (array_key_exists('description', $data)) {
            $exercise->setDescription($data['description']);
        }

        $dm->flush();

        return new JsonResponse(['message' => 'Exercise updated']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function deleteExercise(string $id, DocumentManager $dm): JsonResponse
    {
        $exercise = $dm->getRepository(Exercise::class)->find($id);
        if (!$exercise) {
            return new JsonResponse(['error' => 'Exercise not found'], 404);
        }

        $dm->remove($exercise);
        $dm->flush();

        return new JsonResponse(['message' => 'Exercise deleted']);
    }
}
