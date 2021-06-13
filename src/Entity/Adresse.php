<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdresseRepository::class)
 */
class Adresse
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
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=Gouvernorat::class, inversedBy="adresses")
     */
    private $idGouvernorat;

    /**
     * @ORM\Column(type="integer")
     */
    private $codePostal;

    /**
     * @ORM\ManyToOne(targetEntity=ville::class, inversedBy="adresses")
     */
    private $idVille;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getIdGouvernorat(): ?Gouvernorat
    {
        return $this->idGouvernorat;
    }

    public function setIdGouvernorat(?Gouvernorat $idGouvernorat): self
    {
        $this->idGouvernorat = $idGouvernorat;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(int $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getIdVille(): ?ville
    {
        return $this->idVille;
    }

    public function setIdVille(?ville $idVille): self
    {
        $this->idVille = $idVille;

        return $this;
    }
}
