<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipeRepository::class)
 */
class Equipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\OneToMany(targetEntity=Joueur::class, mappedBy="equipe", cascade={"persist", "remove"})
     */
    private $joueurs;

    /**
     * @ORM\OneToMany(targetEntity=Matche::class, mappedBy="equipe1")
     */
    private $matchesEquipe1;

    /**
     * @ORM\OneToMany(targetEntity=Matche::class, mappedBy="equipe2")
     */
    private $matchesEquipe2;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="equipe", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=But::class, mappedBy="equipeAuMomentDuBut")
     */
    private $buts;

    public function __construct()
    {
        $this->joueurs = new ArrayCollection();
        $this->matchesEquipe1 = new ArrayCollection();
        $this->matchesEquipe2 = new ArrayCollection();
        $this->buts = new ArrayCollection();
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return Collection<int, Joueur>
     */
    public function getJoueurs(): Collection
    {
        return $this->joueurs;
    }

    public function addJoueur(Joueur $joueur): self
    {
        if (!$this->joueurs->contains($joueur)) {
            $this->joueurs[] = $joueur;
            $joueur->setEquipe($this);
        }

        return $this;
    }

    public function removeJoueur(Joueur $joueur): self
    {
        if ($this->joueurs->removeElement($joueur)) {
            if ($joueur->getEquipe() === $this) {
                $joueur->setEquipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Matche>
     */
    public function getMatchesEquipe1(): Collection
    {
        return $this->matchesEquipe1;
    }

    public function addMatchEquipe1(Matche $match): self
    {
        if (!$this->matchesEquipe1->contains($match)) {
            $this->matchesEquipe1[] = $match;
            $match->setEquipe1($this);
        }

        return $this;
    }

    public function removeMatchEquipe1(Matche $match): self
    {
        if ($this->matchesEquipe1->removeElement($match)) {
            if ($match->getEquipe1() === $this) {
                $match->setEquipe1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Matche>
     */
    public function getMatchesEquipe2(): Collection
    {
        return $this->matchesEquipe2;
    }

    public function addMatchEquipe2(Matche $match): self
    {
        if (!$this->matchesEquipe2->contains($match)) {
            $this->matchesEquipe2[] = $match;
            $match->setEquipe2($this);
        }

        return $this;
    }

    public function removeMatchEquipe2(Matche $match): self
    {
        if ($this->matchesEquipe2->removeElement($match)) {
            if ($match->getEquipe2() === $this) {
                $match->setEquipe2(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        if ($user === null && $this->user !== null) {
            $this->user->setEquipe(null);
        }

        if ($user !== null && $user->getEquipe() !== $this) {
            $user->setEquipe($this);
        }

        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, But>
     */
    public function getButs(): Collection
    {
        return $this->buts;
    }

    public function addBut(But $but): self
    {
        if (!$this->buts->contains($but)) {
            $this->buts[] = $but;
            $but->setEquipeAuMomentDuBut($this);
        }

        return $this;
    }

    public function removeBut(But $but): self
    {
        if ($this->buts->removeElement($but)) {
            if ($but->getEquipeAuMomentDuBut() === $this) {
                $but->setEquipeAuMomentDuBut(null);
            }
        }

        return $this;
    }
}
