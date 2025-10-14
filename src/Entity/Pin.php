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

    #[ORM\Column(type:"datetime")]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "pins")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    // -------------------- RELACIÓN CON IMÁGENES --------------------
    #[ORM\OneToMany(mappedBy: 'pin', targetEntity: PinImage::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $images;

    // -------------------- NUEVA RELACIÓN CON ARTISTA --------------------
    #[ORM\ManyToOne(targetEntity: Artista::class, inversedBy: 'pins')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Artista $artista = null; // 🔥 Cambiado: antes era ManyToMany

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    // ---------------------- GETTERS / SETTERS ----------------------
    public function getId(): ?int { return $this->id; }

    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): self { $this->title = $title; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }

    public function getLatitude(): ?float { return $this->latitude; }
    public function setLatitude(float $latitude): self { $this->latitude = $latitude; return $this; }

    public function getLongitude(): ?float { return $this->longitude; }
    public function setLongitude(float $longitude): self { $this->longitude = $longitude; return $this; }

    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(\DateTimeInterface $createdAt): self { $this->createdAt = $createdAt; return $this; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): self { $this->user = $user; return $this; }

    // ---------------------- MÉTODOS PARA IMÁGENES ----------------------
    /** @return Collection<int, PinImage> */
    public function getImages(): Collection { return $this->images; }

    public function addImage(PinImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setPin($this);
        }
        return $this;
    }

    public function removeImage(PinImage $image): self
    {
        if ($this->images->removeElement($image)) {
            if ($image->getPin() === $this) {
                $image->setPin(null);

                // Borrar archivo del disco
                $filename = $image->getFilename();
                if ($filename) {
                    $filePath = __DIR__.'/../../public/uploads/pins/'.$filename;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        }
        return $this;
    }

    // ---------------------- MÉTODOS PARA ARTISTA ----------------------
    public function getArtista(): ?Artista
    {
        return $this->artista;
    }

    public function setArtista(?Artista $artista): self
    {
        $this->artista = $artista;
        return $this;
    }

    public function __toString(): string
    {
        return $this->title ?? 'Pin #'.$this->id;
    }
}
