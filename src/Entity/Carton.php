<?php

namespace App\Entity;

use App\Repository\CartonRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartonRepository::class)
 */
class Carton
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $minute;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $couleur;

    /**
     * @ORM\ManyToOne(targetEntity=Joueur::class, inversedBy="cartons", cascade={"persist", "remove"})
     */
    private $joueur;

    /**
     * @ORM\ManyToOne(targetEntity=Matche::class, inversedBy="cartons", cascade={"persist", "remove"})
     */
    private $matche;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getJoueur(): ?Joueur
    {
        return $this->joueur;
    }

    public function setJoueur(?Joueur $joueur): self
    {
        $this->joueur = $joueur;

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
}
