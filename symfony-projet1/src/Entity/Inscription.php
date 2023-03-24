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

    public function getLemploye(): ?Employe
    {
        return $this->lemploye;
    }

    public function setLemploye(?Employe $lemploye): self
    {
        $this->lemploye = $lemploye;

        return $this;
    }

    public function getLaFromation(): ?Formation
    {
        return $this->laFromation;
    }

    public function setLaFormation(?Formation $laFromation): self
    {
        $this->laFromation = $laFromation;

        return $this;
    }
}