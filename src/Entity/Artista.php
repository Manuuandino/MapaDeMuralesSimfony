<?php

namespace App\Entity;

use App\Repository\ArtistaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArtistaRepository::class)]
class Artista
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:255, unique:true)]
    private ?string $nombre = null;

    #[ORM\OneToMany(mappedBy: 'artista', targetEntity: Pin::class)]
    private Collection $pins;

    public function __construct()
    {
        $this->pins = new ArrayCollection();
    }

    // ---------------------- GETTERS / SETTERS ----------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    /** @return Collection<int, Pin> */
    public function getPins(): Collection
    {
        return $this->pins;
    }

    public function addPin(Pin $pin): self
    {
        if (!$this->pins->contains($pin)) {
            $this->pins->add($pin);
            $pin->setArtista($this); // sincroniza la relación inversa
        }

        return $this;
    }

    public function removePin(Pin $pin): self
    {
        if ($this->pins->removeElement($pin)) {
            if ($pin->getArtista() === $this) {
                $pin->setArtista(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nombre ?? 'Artista sin nombre';
    }
}
