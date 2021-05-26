<?php

namespace App\Entity;

use App\Repository\DatasetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\OneToMany(targetEntity=DatasetTables::class, mappedBy="id_dataset")
     */
    private $datasetTables;

    public function __construct()
    {
        $this->datasetTables = new ArrayCollection();
    }

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




    /**
     * @return Collection|DatasetTables[]
     */
    public function getDatasetTables(): Collection
    {
        return $this->datasetTables;
    }

    public function addDatasetTable(DatasetTables $datasetTable): self
    {
        if (!$this->datasetTables->contains($datasetTable)) {
            $this->datasetTables[] = $datasetTable;
            $datasetTable->setIdDataset($this);
        }

        return $this;
    }

    public function removeDatasetTable(DatasetTables $datasetTable): self
    {
        if ($this->datasetTables->removeElement($datasetTable)) {
            // set the owning side to null (unless already changed)
            if ($datasetTable->getIdDataset() === $this) {
                $datasetTable->setIdDataset(null);
            }
        }

        return $this;
    }
}
