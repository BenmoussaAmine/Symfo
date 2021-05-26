<?php

namespace App\Entity;

use App\Repository\DatasetTablesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DatasetTablesRepository::class)
 */
class DatasetTables
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Dataset::class, inversedBy="datasetTables")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_dataset;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $table_name;

    /**
     * @ORM\Column(type="string", length=255 ,  nullable=true)
     */
    private $cle_jointure;

    /**
     * @ORM\OneToMany(targetEntity=DatasetTablesFields::class, mappedBy="id_dataset_table")
     */
    private $datasetTablesFields;

    public function __construct()
    {
        $this->datasetTablesFields = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdDataset(): ?Dataset
    {
        return $this->id_dataset;
    }

    public function setIdDataset(?Dataset $id_dataset): self
    {
        $this->id_dataset = $id_dataset;

        return $this;
    }

    public function getTableName(): ?string
    {
        return $this->table_name;
    }

    public function setTableName(string $table_name): self
    {
        $this->table_name = $table_name;

        return $this;
    }

    public function getCleJointure(): ?string
    {
        return $this->cle_jointure;
    }

    public function setCleJointure(string $cle_jointure): self
    {
        $this->cle_jointure = $cle_jointure;

        return $this;
    }

    /**
     * @return Collection|DatasetTablesFields[]
     */
    public function getDatasetTablesFields(): Collection
    {
        return $this->datasetTablesFields;
    }

    public function addDatasetTablesField(DatasetTablesFields $datasetTablesField): self
    {
        if (!$this->datasetTablesFields->contains($datasetTablesField)) {
            $this->datasetTablesFields[] = $datasetTablesField;
            $datasetTablesField->setIdDatasetTable($this);
        }

        return $this;
    }

    public function removeDatasetTablesField(DatasetTablesFields $datasetTablesField): self
    {
        if ($this->datasetTablesFields->removeElement($datasetTablesField)) {
            // set the owning side to null (unless already changed)
            if ($datasetTablesField->getIdDatasetTable() === $this) {
                $datasetTablesField->setIdDatasetTable(null);
            }
        }

        return $this;
    }
}
