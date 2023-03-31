<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column]
    private ?int $nbre_heures = null;

    #[ORM\Column(length: 50)]
    private ?string $departement = null;

    #[ORM\Column]
    private ?int $le_produit_id = null;

    #[ORM\Column(length: 50)]
    private ?string $formation = null;

    #[ORM\Column(length: 50)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: Produit::class)]
    #[ORM\JoinColumn(nullable: true)]
    private $leProduit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getNbreHeures(): ?int
    {
        return $this->nbre_heures;
    }

    public function setNbreHeures(int $nbre_heures): self
    {
        $this->nbre_heures = $nbre_heures;

        return $this;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(string $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    public function getLeProduitId(): ?int
    {
        return $this->le_produit_id;
    }

    public function setLeProduitId(int $le_produit_id): self
    {
        $this->le_produit_id = $le_produit_id;

        return $this;
    }

    public function getFormation(): ?string
    {
        return $this->formation;
    }

    public function setFormation(string $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLeProduit(): ?Produit
    {
        return $this->leProduit;
    }

    public function setLeProduit(?Produit $leProduit): self
    {
        $this->leProduit = $leProduit;

        return $this;
    }
}
