<?php

namespace App\Entity;

use App\Repository\FeuilleMatchRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FeuilleMatchRepository::class)
 */
class FeuilleMatch
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $titulairesEquipe1 = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $remplacantsEquipe1 = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $dirigeantsEquipe1 = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $titulairesEquipe2 = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $remplacantsEquipe2 = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $dirigeantsEquipe2 = [];

    /**
     * @ORM\OneToOne(targetEntity=Matche::class, cascade={"persist", "remove"})
     */
    private $matche;

    /**
     * @ORM\Column(type="json")
     */
    private $numerosMaillot = [];

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $signatureArbitre;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $signatureavantEquipe1;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $signatureavantEquipe2;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $signatureapresEquipe1;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $signatureapresEquipe2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reserveEquipe1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reserveEquipe2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observationArbitre;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $historiqueJoueurs = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulairesEquipe1(): ?array
    {
        return $this->titulairesEquipe1;
    }

    public function setTitulairesEquipe1(?array $titulairesEquipe1): self
    {
        $this->titulairesEquipe1 = $titulairesEquipe1;

        return $this;
    }

    public function getRemplacantsEquipe1(): ?array
    {
        return $this->remplacantsEquipe1;
    }

    public function setRemplacantsEquipe1(?array $remplacantsEquipe1): self
    {
        $this->remplacantsEquipe1 = $remplacantsEquipe1;

        return $this;
    }

    public function getDirigeantsEquipe1(): ?array
    {
        return $this->dirigeantsEquipe1;
    }

    public function setDirigeantsEquipe1(?array $dirigeantsEquipe1): self
    {
        $this->dirigeantsEquipe1 = $dirigeantsEquipe1;

        return $this;
    }

    public function getTitulairesEquipe2(): ?array
    {
        return $this->titulairesEquipe2;
    }

    public function setTitulairesEquipe2(?array $titulairesEquipe2): self
    {
        $this->titulairesEquipe2 = $titulairesEquipe2;

        return $this;
    }

    public function getRemplacantsEquipe2(): ?array
    {
        return $this->remplacantsEquipe2;
    }

    public function setRemplacantsEquipe2(?array $remplacantsEquipe2): self
    {
        $this->remplacantsEquipe2 = $remplacantsEquipe2;

        return $this;
    }

    public function getDirigeantsEquipe2(): ?array
    {
        return $this->dirigeantsEquipe2;
    }

    public function setDirigeantsEquipe2(?array $dirigeantsEquipe2): self
    {
        $this->dirigeantsEquipe2 = $dirigeantsEquipe2;

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

    public function getNumerosMaillot(): array
    {
        return $this->numerosMaillot;
    }

    public function setNumerosMaillot(array $numerosMaillot): self
    {
        $this->numerosMaillot = $numerosMaillot;
        return $this;
    }

    public function getNumeroMaillotForJoueur(int $joueurId): ?int
    {
        return $this->numerosMaillot[$joueurId] ?? null;
    }


    public function isSignatureArbitre(): ?bool
    {
        return $this->signatureArbitre;
    }

    public function setSignatureArbitre(?bool $signatureArbitre): self
    {
        $this->signatureArbitre = $signatureArbitre;

        return $this;
    }

    public function isSignatureavantEquipe1(): ?bool
    {
        return $this->signatureavantEquipe1;
    }

    public function setSignatureavantEquipe1(?bool $signatureavantEquipe1): self
    {
        $this->signatureavantEquipe1 = $signatureavantEquipe1;

        return $this;
    }

    public function isSignatureavantEquipe2(): ?bool
    {
        return $this->signatureavantEquipe2;
    }

    public function setSignatureavantEquipe2(?bool $signatureavantEquipe2): self
    {
        $this->signatureavantEquipe2 = $signatureavantEquipe2;

        return $this;
    }

    public function isSignatureapresEquipe1(): ?bool
    {
        return $this->signatureapresEquipe1;
    }

    public function setSignatureapresEquipe1(?bool $signatureapresEquipe1): self
    {
        $this->signatureapresEquipe1 = $signatureapresEquipe1;

        return $this;
    }

    public function isSignatureapresEquipe2(): ?bool
    {
        return $this->signatureapresEquipe2;
    }

    public function setSignatureapresEquipe2(?bool $signatureapresEquipe2): self
    {
        $this->signatureapresEquipe2 = $signatureapresEquipe2;

        return $this;
    }

    public function getReserveEquipe1(): ?string
    {
        return $this->reserveEquipe1;
    }

    public function setReserveEquipe1(?string $reserveEquipe1): self
    {
        $this->reserveEquipe1 = $reserveEquipe1;

        return $this;
    }

    public function getReserveEquipe2(): ?string
    {
        return $this->reserveEquipe2;
    }

    public function setReserveEquipe2(?string $reserveEquipe2): self
    {
        $this->reserveEquipe2 = $reserveEquipe2;

        return $this;
    }

    public function getObservationArbitre(): ?string
    {
        return $this->observationArbitre;
    }

    public function setObservationArbitre(?string $observationArbitre): self
    {
        $this->observationArbitre = $observationArbitre;

        return $this;
    }

    public function getHistoriqueJoueurs(): array
    {
        return $this->historiqueJoueurs ?? [];
    }

    public function setHistoriqueJoueurs(array $historiqueJoueurs): self
    {
        $this->historiqueJoueurs = $historiqueJoueurs;
        return $this;
    }
}
