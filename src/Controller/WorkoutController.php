<?php

namespace App\Controller;

use App\Document\Workout;
use App\Document\Exercise;
use App\Document\ExerciseEntry;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/workouts')]
class WorkoutController extends AbstractController
{
    #[Route('', name: 'workout_index', methods: ['GET'])]
    public function index(DocumentManager $dm): JsonResponse
    {
        $workouts = $dm->getRepository(Workout::class)->findAll();
        return $this->json($workouts, 200, [], ['groups' => 'workout:read']);
    }

    #[Route('', name: 'workout_create', methods: ['POST'])]
    public function create(Request $request, DocumentManager $dm): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $workout = new Workout();
        $workout->setName($data['name'] ?? 'Nouvelle séance');

        foreach ($data['exercises'] ?? [] as $exData) {
            $exercise = $dm->getRepository(Exercise::class)->find($exData['exercise_id']);

            if (!$exercise) {
                return $this->json(['error' => 'Exercice non trouvé'], 404);
            }

            $entry = new ExerciseEntry();
            $entry->setExercise($exercise)
                  ->setRepetitions($exData['repetitions'])
                  ->setSeries($exData['series'])
                  ->setWeight($exData['weight']);

            $workout->addExercise($entry);
        }

        $dm->persist($workout);
        $dm->flush();

        return $this->json($workout, 201, [], ['groups' => 'workout:read']);
    }

    #[Route('/{id}', name: 'workout_show', methods: ['GET'])]
    public function show(string $id, DocumentManager $dm): JsonResponse
    {
        $workout = $dm->getRepository(Workout::class)->find($id);

        if (!$workout) {
            return $this->json(['error' => 'Séance non trouvée'], 404);
        }

        return $this->json($workout, 200, [], ['groups' => 'workout:read']);
    }

    #[Route('/{id}', name: 'workout_update', methods: ['PUT'])]
    public function update(string $id, Request $request, DocumentManager $dm): JsonResponse
    {
        $workout = $dm->getRepository(Workout::class)->find($id);

        if (!$workout) {
            return $this->json(['error' => 'Séance non trouvée'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $workout->setName($data['name'] ?? $workout->getName());

        $workout->getExercises()->clear(); // Remplace tous les exercices

        foreach ($data['exercises'] ?? [] as $exData) {
            $exercise = $dm->getRepository(Exercise::class)->find($exData['exercise_id']);

            if (!$exercise) {
                return $this->json(['error' => 'Exercice non trouvé'], 404);
            }

            $entry = new ExerciseEntry();
            $entry->setExercise($exercise)
                  ->setRepetitions($exData['repetitions'])
                  ->setSeries($exData['series'])
                  ->setWeight($exData['weight']);

            $workout->addExercise($entry);
        }

        $dm->flush();

        return $this->json($workout, 200, [], ['groups' => 'workout:read']);
    }

    #[Route('/{id}', name: 'workout_delete', methods: ['DELETE'])]
    public function delete(string $id, DocumentManager $dm): JsonResponse
    {
        $workout = $dm->getRepository(Workout::class)->find($id);

        if (!$workout) {
            return $this->json(['error' => 'Séance non trouvée'], 404);
        }

        $dm->remove($workout);
        $dm->flush();

        return $this->json(['message' => 'Séance supprimée']);
    }
}
