<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InscriptionRepository::class)]
class Inscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = null;

    #[ORM\Column]
    private ?int $lemploye_id = null;

    #[ORM\Column]
    private ?int $la_fromation_id = null;

    #[ORM\ManyToOne(targetEntity: Employe::class)]
    #[ORM\JoinColumn(nullable: true)]
    private $lemploye = null;

    #[ORM\ManyToOne(targetEntity: Formation::class)]
    #[ORM\JoinColumn(nullable: true)]
    private $laFromation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getLemployeId(): ?int
    {
        return $this->lemploye_id;
    }

    public function setLemployeId(int $lemploye_id): self
    {
        $this->lemploye_id = $lemploye_id;

        return $this;
    }

    public function getLaFromationId(): ?int
    {
        return $this->la_fromation_id;
    }

    public function setLaFromationId(int $la_fromation_id): self
    {
        $this->la_fromation_id = $la_fromation_id;

        return $this;
    }

    public function getLaFromation(): ?Formation
    {
        return $this->laFromation;
    }

    public function setLaFromation(?Formation $laFromation): self
    {
        $this->laFromation = $laFromation;

        return $this;
    }

    public function getLemploye(): ?Employe
    {
        return $this->lemploye;
    }

    public function setLemploye(?Employe $lemploye): self
    {
        $this->lemploye = $lemploye;

        return $this;
    }
}
