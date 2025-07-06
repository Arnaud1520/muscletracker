<?php

namespace App\Controller;

use App\Document\Workout;
use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/workouts', name: 'api_workouts_')]
class WorkoutController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(DocumentManager $dm): JsonResponse
    {
        $workouts = $dm->getRepository(Workout::class)->findAll();
        $data = array_map(fn(Workout $workout) => [
            'id' => $workout->getId(),
            'userId' => $workout->getUser()->getId(),
            'date' => $workout->getDate()->format('Y-m-d'),
            'exercises' => $workout->getExercises(),
        ], $workouts);

        return new JsonResponse($data);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function createWorkout(Request $request, DocumentManager $dm): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = $dm->getRepository(User::class)->find($data['userId'] ?? '');
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        $workout = new Workout();
        $workout->setUser($user);
        $workout->setDate(new \DateTime($data['date'] ?? 'now'));
        $workout->setExercises($data['exercises'] ?? []);

        $dm->persist($workout);
        $dm->flush();

        return new JsonResponse(['message' => 'Workout created', 'id' => $workout->getId()], 201);
    }

    #[Route('/{id}', name: 'get', methods: ['GET'])]
    public function getWorkout(string $id, DocumentManager $dm): JsonResponse
    {
        $workout = $dm->getRepository(Workout::class)->find($id);
        if (!$workout) {
            return new JsonResponse(['error' => 'Workout not found'], 404);
        }

        return new JsonResponse([
            'id' => $workout->getId(),
            'userId' => $workout->getUser()->getId(),
            'date' => $workout->getDate()->format('Y-m-d'),
            'exercises' => $workout->getExercises(),
        ]);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function updateWorkout(string $id, Request $request, DocumentManager $dm): JsonResponse
    {
        $workout = $dm->getRepository(Workout::class)->find($id);
        if (!$workout) {
            return new JsonResponse(['error' => 'Workout not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['userId'])) {
            $user = $dm->getRepository(User::class)->find($data['userId']);
            if (!$user) {
                return new JsonResponse(['error' => 'User not found'], 404);
            }
            $workout->setUser($user);
        }
        if (isset($data['date'])) {
            $workout->setDate(new \DateTime($data['date']));
        }
        if (isset($data['exercises'])) {
            $workout->setExercises($data['exercises']);
        }

        $dm->flush();

        return new JsonResponse(['message' => 'Workout updated']);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function deleteWorkout(string $id, DocumentManager $dm): JsonResponse
    {
        $workout = $dm->getRepository(Workout::class)->find($id);
        if (!$workout) {
            return new JsonResponse(['error' => 'Workout not found'], 404);
        }

        $dm->remove($workout);
        $dm->flush();

        return new JsonResponse(['message' => 'Workout deleted']);
    }

    #[Route('/{id}/add-exercise', name: 'add_exercise', methods: ['POST'])]
public function addExercise(string $id, Request $request, DocumentManager $dm): JsonResponse
{
    $workout = $dm->getRepository(Workout::class)->find($id);
    if (!$workout) {
        return new JsonResponse(['error' => 'Workout not found'], 404);
    }

    $data = json_decode($request->getContent(), true);
    $exerciseId = $data['exerciseId'] ?? null;

    if (!$exerciseId) {
        return new JsonResponse(['error' => 'exerciseId is required'], 400);
    }

    $exercise = $dm->getRepository(\App\Document\Exercise::class)->find($exerciseId);
    if (!$exercise) {
        return new JsonResponse(['error' => 'Exercise not found'], 404);
    }

    // Exemple basique : pas de sets, reps, poids
    $workout->addExercise([
        'exerciseId' => $exercise->getId(),
        'nom' => $exercise->getNom(),
        'categorie' => $exercise->getCategorie(),
    ]);

    $dm->flush();

    return new JsonResponse(['message' => 'Exercise added']);
}

}
