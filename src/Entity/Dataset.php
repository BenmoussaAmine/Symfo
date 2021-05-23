<?php

namespace App\Entity;

use App\Repository\DatasetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DatasetRepository::class)
 */
class Dataset
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255 , unique=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $tables = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTables(): ?array
    {
        return $this->tables;
    }

    public function setTables(?array $tables): self
    {
        $this->tables = $tables;

        return $this;
    }
}
