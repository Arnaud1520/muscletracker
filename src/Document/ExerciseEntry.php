<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Serializer\Annotation\Groups;

#[MongoDB\EmbeddedDocument]
class ExerciseEntry
{
    #[MongoDB\ReferenceOne(targetDocument: Exercise::class)]
    #[Groups(['workout:read', 'workout:write'])]
    private ?Exercise $exercise = null;

    #[MongoDB\Field(type: "int")]
    #[Groups(['workout:read', 'workout:write'])]
    private int $repetitions;

    #[MongoDB\Field(type: "int")]
    #[Groups(['workout:read', 'workout:write'])]
    private int $series;

    #[MongoDB\Field(type: "float")]
    #[Groups(['workout:read', 'workout:write'])]
    private float $weight;

    public function getExercise(): ?Exercise
    {
        return $this->exercise;
    }

    public function setExercise(?Exercise $exercise): self
    {
        $this->exercise = $exercise;
        return $this;
    }

    public function getRepetitions(): int
    {
        return $this->repetitions;
    }

    public function setRepetitions(int $repetitions): self
    {
        $this->repetitions = $repetitions;
        return $this;
    }

    public function getSeries(): int
    {
        return $this->series;
    }

    public function setSeries(int $series): self
    {
        $this->series = $series;
        return $this;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;
        return $this;
    }
}
