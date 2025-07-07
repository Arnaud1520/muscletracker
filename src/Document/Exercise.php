<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Document\User;
use Symfony\Component\Serializer\Annotation\Groups;

#[MongoDB\Document(collection: "exercises")]
class Exercise
{
    #[MongoDB\Id]
    #[Groups(['exercise:read', 'workout:read'])]
    private ?string $id = null;

    #[MongoDB\Field(type: "string")]
    #[Groups(['exercise:read', 'workout:read'])]
    private string $nom;

    #[MongoDB\Field(type: "string")]
    #[Groups(['exercise:read', 'workout:read'])]
    private string $categorie;

    #[MongoDB\Field(type: "string", nullable: true)]
    #[Groups(['exercise:read', 'workout:read'])]
    private ?string $description = null;

    #[MongoDB\ReferenceOne(targetDocument: User::class)]
    private ?User $user = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getCategorie(): string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
