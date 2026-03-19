<?php

namespace App\Entity;

use App\Repository\ParcoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParcoursRepository::class)]
class Parcours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descriptionParcours = null;

    #[ORM\Column(length: 255)]
    private ?string $imageParcours = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $dureeEstime = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateCreation = null;

    #[ORM\ManyToOne(inversedBy: 'parcours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    /**
     * @var Collection<int, Lieu>
     */
    #[ORM\ManyToMany(targetEntity: Lieu::class, inversedBy: 'parcours')]
    private Collection $lieux;

    /**
     * @var Collection<int, Utilisateur>
     */
    #[ORM\ManyToMany(targetEntity: Utilisateur::class, mappedBy: 'parcoursVus')]
    private Collection $utilisateursConsultants;

    public function __construct()
    {
        $this->lieux = new ArrayCollection();
        $this->utilisateursConsultants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescriptionParcours(): ?string
    {
        return $this->descriptionParcours;
    }

    public function setDescriptionParcours(string $descriptionParcours): static
    {
        $this->descriptionParcours = $descriptionParcours;

        return $this;
    }

    public function getImageParcours(): ?string
    {
        return $this->imageParcours;
    }

    public function setImageParcours(string $imageParcours): static
    {
        $this->imageParcours = $imageParcours;

        return $this;
    }

    public function getDureeEstime(): ?string
    {
        return $this->dureeEstime;
    }

    public function setDureeEstime(?string $dureeEstime): static
    {
        $this->dureeEstime = $dureeEstime;

        return $this;
    }

    public function getDateCreation(): ?\DateTime
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTime $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * @return Collection<int, Lieu>
     */
    public function getLieux(): Collection
    {
        return $this->lieux;
    }

    public function addLieux(Lieu $lieux): static
    {
        if (!$this->lieux->contains($lieux)) {
            $this->lieux->add($lieux);
        }

        return $this;
    }

    public function removeLieux(Lieu $lieux): static
    {
        $this->lieux->removeElement($lieux);

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getUtilisateursConsultants(): Collection
    {
        return $this->utilisateursConsultants;
    }

    public function addUtilisateursConsultant(Utilisateur $utilisateursConsultant): static
    {
        if (!$this->utilisateursConsultants->contains($utilisateursConsultant)) {
            $this->utilisateursConsultants->add($utilisateursConsultant);
            $utilisateursConsultant->addParcoursVu($this);
        }

        return $this;
    }

    public function removeUtilisateursConsultant(Utilisateur $utilisateursConsultant): static
    {
        if ($this->utilisateursConsultants->removeElement($utilisateursConsultant)) {
            $utilisateursConsultant->removeParcoursVu($this);
        }

        return $this;
    }
}
