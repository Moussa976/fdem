<?php

namespace App\Entity;

use App\Repository\JoueurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JoueurRepository::class)
 */
class Joueur
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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numero;

    /**
     * @ORM\ManyToOne(targetEntity=Equipe::class, inversedBy="joueurs")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $equipe;

    /**
     * @ORM\OneToMany(targetEntity=But::class, mappedBy="joueur", cascade={"persist", "remove"})
     */
    private $buts;

    /**
     * @ORM\OneToMany(targetEntity=Carton::class, mappedBy="joueur", cascade={"persist", "remove"})
     */
    private $cartons;

    /**
     * @ORM\OneToMany(targetEntity=Remplacement::class, mappedBy="joueurEntrant", cascade={"persist", "remove"})
     */
    private $remplacementsJoueurEntrant;

    /**
     * @ORM\OneToMany(targetEntity=Remplacement::class, mappedBy="joueurSortant", cascade={"persist", "remove"})
     */
    private $remplacementsJoueurSortant;

    public function __construct()
    {
        $this->buts = new ArrayCollection();
        $this->cartons = new ArrayCollection();
        $this->remplacementsJoueurEntrant = new ArrayCollection();
        $this->remplacementsJoueurSortant = new ArrayCollection();
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

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(?int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getEquipe(): ?Equipe
    {
        return $this->equipe;
    }

    public function setEquipe(?Equipe $equipe): self
    {
        $this->equipe = $equipe;

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
            $but->setJoueur($this);
        }

        return $this;
    }

    public function removeBut(But $but): self
    {
        if ($this->buts->removeElement($but)) {
            if ($but->getJoueur() === $this) {
                $but->setJoueur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Carton>
     */
    public function getCartons(): Collection
    {
        return $this->cartons;
    }

    public function addCarton(Carton $carton): self
    {
        if (!$this->cartons->contains($carton)) {
            $this->cartons[] = $carton;
            $carton->setJoueur($this);
        }

        return $this;
    }

    public function removeCarton(Carton $carton): self
    {
        if ($this->cartons->removeElement($carton)) {
            if ($carton->getJoueur() === $this) {
                $carton->setJoueur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Remplacement>
     */
    public function getRemplacementsJoueurEntrant(): Collection
    {
        return $this->remplacementsJoueurEntrant;
    }

    public function addRemplacementJoueurEntrant(Remplacement $remplacement): self
    {
        if (!$this->remplacementsJoueurEntrant->contains($remplacement)) {
            $this->remplacementsJoueurEntrant[] = $remplacement;
            $remplacement->setJoueurEntrant($this);
        }

        return $this;
    }

    public function removeRemplacementJoueurEntrant(Remplacement $remplacement): self
    {
        if ($this->remplacementsJoueurEntrant->removeElement($remplacement)) {
            if ($remplacement->getJoueurEntrant() === $this) {
                $remplacement->setJoueurEntrant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Remplacement>
     */
    public function getRemplacementsJoueurSortant(): Collection
    {
        return $this->remplacementsJoueurSortant;
    }

    public function addRemplacementJoueurSortant(Remplacement $remplacement): self
    {
        if (!$this->remplacementsJoueurSortant->contains($remplacement)) {
            $this->remplacementsJoueurSortant[] = $remplacement;
            $remplacement->setJoueurSortant($this);
        }

        return $this;
    }

    public function removeRemplacementJoueurSortant(Remplacement $remplacement): self
    {
        if ($this->remplacementsJoueurSortant->removeElement($remplacement)) {
            if ($remplacement->getJoueurSortant() === $this) {
                $remplacement->setJoueurSortant(null);
            }
        }

        return $this;
    }
}
