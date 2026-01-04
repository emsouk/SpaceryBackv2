<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LieuRepository::class)]
class Lieu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $rue = null;

    #[ORM\Column(length: 50)]
    private ?string $code_postal = null;

    #[ORM\Column(length: 50)]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    private ?string $image_lieu = null;

    #[ORM\Column]
    private ?bool $payant = null;

    #[ORM\Column]
    private ?\DateTime $creerLe = null;

    #[ORM\Column]
    private ?\DateTime $majLe = null;

    #[ORM\Column(length: 50)]
    private ?string $pays = null;

    #[ORM\Column]
    private ?float $LatLong = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $site_web = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $horaires = null;

    /**
     * @var Collection<int, Parcours>
     */
    #[ORM\ManyToMany(targetEntity: Parcours::class, mappedBy: 'lieux')]
    private Collection $parcours;

    public function __construct()
    {
        $this->parcours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): static
    {
        $this->rue = $rue;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->code_postal;
    }

    public function setCodePostal(string $code_postal): static
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getImageLieu(): ?string
    {
        return $this->image_lieu;
    }

    public function setImageLieu(string $image_lieu): static
    {
        $this->image_lieu = $image_lieu;

        return $this;
    }

    public function isPayant(): ?bool
    {
        return $this->payant;
    }

    public function setPayant(bool $payant): static
    {
        $this->payant = $payant;

        return $this;
    }

    public function getCreerLe(): ?\DateTime
    {
        return $this->creerLe;
    }

    public function setCreerLe(\DateTime $creerLe): static
    {
        $this->creerLe = $creerLe;

        return $this;
    }

    public function getMajLe(): ?\DateTime
    {
        return $this->majLe;
    }

    public function setMajLe(\DateTime $majLe): static
    {
        $this->majLe = $majLe;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getLatLong(): ?int
    {
        return $this->LatLong;
    }

    public function setLatLong(int $LatLong): static
    {
        $this->LatLong = $LatLong;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSiteWeb(): ?string
    {
        return $this->site_web;
    }

    public function setSiteWeb(?string $site_web): static
    {
        $this->site_web = $site_web;

        return $this;
    }

    public function getHoraires(): ?string
    {
        return $this->horaires;
    }

    public function setHoraires(?string $horaires): static
    {
        $this->horaires = $horaires;

        return $this;
    }

    #[ORM\ManyToOne(inversedBy: 'lieux')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Type $type = null;

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Parcours>
     */
    public function getParcours(): Collection
    {
        return $this->parcours;
    }

    public function addParcour(Parcours $parcour): static
    {
        if (!$this->parcours->contains($parcour)) {
            $this->parcours->add($parcour);
            $parcour->addLieux($this);
        }

        return $this;
    }

    public function removeParcour(Parcours $parcour): static
    {
        if ($this->parcours->removeElement($parcour)) {
            $parcour->removeLieux($this);
        }

        return $this;
    }
}
