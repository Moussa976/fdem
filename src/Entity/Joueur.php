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
     * @ORM\ManyToOne(targetEntity=Equipe::class, inversedBy="joueurs", cascade={"persist", "remove"})
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
     * @ORM\OneToMany(targetEntity=Remplacement::class, mappedBy="joueur_sortant", cascade={"persist", "remove"})
     */
    private $remplacements;

    public function __construct()
    {
        $this->buts = new ArrayCollection();
        $this->cartons = new ArrayCollection();
        $this->remplacements = new ArrayCollection();
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
            // set the owning side to null (unless already changed)
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
            // set the owning side to null (unless already changed)
            if ($carton->getJoueur() === $this) {
                $carton->setJoueur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Remplacement>
     */
    public function getRemplacements(): Collection
    {
        return $this->remplacements;
    }

    public function addRemplacement(Remplacement $remplacement): self
    {
        if (!$this->remplacements->contains($remplacement)) {
            $this->remplacements[] = $remplacement;
            $remplacement->setJoueurSortant($this);
        }

        return $this;
    }

    public function removeRemplacement(Remplacement $remplacement): self
    {
        if ($this->remplacements->removeElement($remplacement)) {
            // set the owning side to null (unless already changed)
            if ($remplacement->getJoueurSortant() === $this) {
                $remplacement->setJoueurSortant(null);
            }
        }

        return $this;
    }
}
