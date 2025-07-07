<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

#[MongoDB\Document(collection: "workouts")]
class Workout
{
    #[MongoDB\Id]
    #[Groups(['workout:read'])]
    private ?string $id = null;

    #[MongoDB\Field(type: "string")]
    #[Groups(['workout:read', 'workout:write'])]
    private string $name;

    #[MongoDB\EmbedMany(targetDocument: ExerciseEntry::class)]
    #[Groups(['workout:read', 'workout:write'])]
    private Collection $exercises;

    public function __construct()
    {
        $this->exercises = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection|ExerciseEntry[]
     */
    public function getExercises(): Collection
    {
        return $this->exercises;
    }

    public function addExercise(ExerciseEntry $exercise): self
    {
        if (!$this->exercises->contains($exercise)) {
            $this->exercises[] = $exercise;
        }
        return $this;
    }

    public function removeExercise(ExerciseEntry $exercise): self
    {
        $this->exercises->removeElement($exercise);
        return $this;
    }
}
