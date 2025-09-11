<?php

namespace App\Entity;

use App\Repository\PinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PinRepository::class)]
class Pin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $title = null;

    #[ORM\Column(type:"text", nullable:true)]
    private ?string $description = null;

    #[ORM\Column(type:"float")]
    private ?float $latitude = null;

    #[ORM\Column(type:"float")]
    private ?float $longitude = null;

    #[ORM\Column(type:"string", nullable: true, length: 255)]
    private ?string $image = null;  // <-- primera imagen para mostrar en admin

    #[ORM\Column(type:"datetime")]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "pins")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct() {
        $this->images = new ArrayCollection();
    }

    // ------------------ GETTERS / SETTERS ------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    // ------------------ MULTIPLES IMÁGENES ------------------

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(string $filename): self
    {
        if (!$this->images->contains($filename)) {
            $this->images[] = $filename;
        }
        return $this;
    }

    public function removeImage(string $filename): self
    {
        $this->images->removeElement($filename);
        return $this;
    }
}
