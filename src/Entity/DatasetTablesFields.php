<?php

namespace App\Entity;

use App\Repository\DatasetTablesFieldsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DatasetTablesFieldsRepository::class)
 */
class DatasetTablesFields
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=datasetTables::class, inversedBy="datasetTablesFields")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_dataset_table;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $agregation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdDatasetTable(): ?datasetTables
    {
        return $this->id_dataset_table;
    }

    public function setIdDatasetTable(?datasetTables $id_dataset_table): self
    {
        $this->id_dataset_table = $id_dataset_table;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getAgregation(): ?string
    {
        return $this->agregation;
    }

    public function setAgregation(string $agregation): self
    {
        $this->agregation = $agregation;

        return $this;
    }
}
