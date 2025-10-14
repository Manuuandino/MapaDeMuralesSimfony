<?php

namespace App\Entity;

use App\Repository\PinImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: PinImageRepository::class)]
class PinImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    #[ORM\ManyToOne(targetEntity: Pin::class, inversedBy: "images")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pin $pin = null;

    #[ORM\ManyToOne(targetEntity: Artista::class, inversedBy: 'imagenes')]
    private ?Artista $artista = null;

    // ------------------ propiedad temporal para subir archivo ------------------
    private ?UploadedFile $uploadedFile = null;

    public function getUploadedFile(): ?UploadedFile { return $this->uploadedFile; }
    public function setUploadedFile(?UploadedFile $file): self { $this->uploadedFile = $file; return $this; }

    // ------------------ GETTERS / SETTERS ------------------
    public function getId(): ?int { return $this->id; }
    public function getFilename(): ?string { return $this->filename; }
    public function setFilename(string $filename): self { $this->filename = $filename; return $this; }
    public function getPin(): ?Pin { return $this->pin; }
    public function setPin(?Pin $pin): self { $this->pin = $pin; return $this; }
    public function getArtista(): ?Artista
{
    return $this->artista;
}

public function setArtista(?Artista $artista): self
{
    $this->artista = $artista;
    return $this;
}
}
