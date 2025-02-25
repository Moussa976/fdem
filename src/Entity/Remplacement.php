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
     * @ORM\ManyToOne(targetEntity=Joueur::class, inversedBy="remplacements", cascade={"persist", "remove"})
     */
    private $joueur_sortant;

    /**
     * @ORM\ManyToOne(targetEntity=Joueur::class, inversedBy="remplacements", cascade={"persist", "remove"})
     */
    private $joueur_entrant;

    /**
     * @ORM\ManyToOne(targetEntity=Matche::class, inversedBy="remplacements", cascade={"persist", "remove"})
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
        return $this->joueur_sortant;
    }

    public function setJoueurSortant(?Joueur $joueur_sortant): self
    {
        $this->joueur_sortant = $joueur_sortant;

        return $this;
    }

    public function getJoueurEntrant(): ?Joueur
    {
        return $this->joueur_entrant;
    }

    public function setJoueurEntrant(?Joueur $joueur_entrant): self
    {
        $this->joueur_entrant = $joueur_entrant;

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
