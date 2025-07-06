<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[MongoDB\Document(collection: "workouts")]
class Workout
{
    #[MongoDB\Id]
    private ?string $id = null;

    #[MongoDB\ReferenceOne(targetDocument: User::class)]
    private User $user;

    #[MongoDB\Field(type: "date")]
    private \DateTimeInterface $date;

    /**
     * Liste des exercices avec détails (reps, sets, poids)
     * stockée comme un tableau de sous-documents
     */
    #[MongoDB\Field(type: "collection")]
    private array $exercises = [];

    public function __construct()
    {
        $this->exercises = [];
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Retourne un tableau d'exercices avec reps, sets, poids, ex:
     * [
     *   ['exerciseId' => 'xxx', 'sets' => 3, 'reps' => 12, 'weight' => 50.0],
     *   ...
     * ]
     */
    public function getExercises(): array
    {
        return $this->exercises;
    }

    public function setExercises(array $exercises): self
    {
        $this->exercises = $exercises;

        return $this;
    }

    public function addExercise(array $exercise): self
    {
        $this->exercises[] = $exercise;

        return $this;
    }
}
