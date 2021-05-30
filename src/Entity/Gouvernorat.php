<?php

namespace App\Entity;

use App\Repository\GouvernoratRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GouvernoratRepository::class)
 */
class Gouvernorat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_gouvernorat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomGouvernorat(): ?string
    {
        return $this->nom_gouvernorat;
    }

    public function setNomGouvernorat(string $nom_gouvernorat): self
    {
        $this->nom_gouvernorat = $nom_gouvernorat;

        return $this;
    }
}
