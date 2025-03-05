<?php

namespace App\Entity;

use App\Repository\RemplacementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RemplacementRepository::class)
 */
class Remplacement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Joueur::class, inversedBy="remplacementsJoueurSortant", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $joueurSortant;

    /**
     * @ORM\ManyToOne(targetEntity=Joueur::class, inversedBy="remplacementsJoueurEntrant", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $joueurEntrant;

    /**
     * @ORM\ManyToOne(targetEntity=Matche::class, inversedBy="remplacements", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $matche;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $minute;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJoueurSortant(): ?Joueur
    {
        return $this->joueurSortant;
    }

    public function setJoueurSortant(?Joueur $joueurSortant): self
    {
        $this->joueurSortant = $joueurSortant;

        return $this;
    }

    public function getJoueurEntrant(): ?Joueur
    {
        return $this->joueurEntrant;
    }

    public function setJoueurEntrant(?Joueur $joueurEntrant): self
    {
        $this->joueurEntrant = $joueurEntrant;

        return $this;
    }

    public function getMatche(): ?Matche
    {
        return $this->matche;
    }

    public function setMatche(?Matche $matche): self
    {
        $this->matche = $matche;

        return $this;
    }

    public function getMinute(): ?int
    {
        return $this->minute;
    }

    public function setMinute(?int $minute): self
    {
        $this->minute = $minute;

        return $this;
    }
}
