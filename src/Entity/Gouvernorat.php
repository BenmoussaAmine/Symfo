<?php

namespace App\Entity;

use App\Repository\GouvernoratRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity=Ville::class, mappedBy="idGouvernorat")
     */
    private $villes;

    /**
     * @ORM\OneToMany(targetEntity=Adresse::class, mappedBy="idGouvernorat")
     */
    private $adresses;

    public function __construct()
    {
        $this->villes = new ArrayCollection();
        $this->adresses = new ArrayCollection();
    }

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

    /**
     * @return Collection|Ville[]
     */
    public function getVilles(): Collection
    {
        return $this->villes;
    }

    public function addVille(Ville $ville): self
    {
        if (!$this->villes->contains($ville)) {
            $this->villes[] = $ville;
            $ville->setIdGouvernorat($this);
        }

        return $this;
    }

    public function removeVille(Ville $ville): self
    {
        if ($this->villes->removeElement($ville)) {
            // set the owning side to null (unless already changed)
            if ($ville->getIdGouvernorat() === $this) {
                $ville->setIdGouvernorat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Adresse[]
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Adresse $adress): self
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses[] = $adress;
            $adress->setIdGouvernorat($this);
        }

        return $this;
    }

    public function removeAdress(Adresse $adress): self
    {
        if ($this->adresses->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getIdGouvernorat() === $this) {
                $adress->setIdGouvernorat(null);
            }
        }

        return $this;
    }
}
