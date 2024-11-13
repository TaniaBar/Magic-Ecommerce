<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // #[Assert\NotBlank()]
    // #[Assert\PositiveOrZero()]
    // #[ORM\Column]
    // private ?int $quantite = null;

    // #[Assert\NotBlank()]
    // #[Assert\PositiveOrZero()]
    // #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    // private ?string $prix = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $cree_le = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, PanierProduit>
     */
    #[ORM\OneToMany(targetEntity: PanierProduit::class, mappedBy: 'panier')]
    private Collection $panierProduits;

    public function __construct()
    {
        $this->panierProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    // public function getQuantite(): ?int
    // {
    //     return $this->quantite;
    // }

    // public function setQuantite(int $quantite): static
    // {
    //     $this->quantite = $quantite;

    //     return $this;
    // }

    // public function getPrix(): ?string
    // {
    //     return $this->prix;
    // }

    // public function setPrix(string $prix): static
    // {
    //     $this->prix = $prix;

    //     return $this;
    // }

    public function getCreeLe(): ?\DateTimeImmutable
    {
        return $this->cree_le;
    }

    public function setCreeLe(\DateTimeImmutable $cree_le): static
    {
        $this->cree_le = $cree_le;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, PanierProduit>
     */
    public function getPanierProduits(): Collection
    {
        return $this->panierProduits;
    }

    public function addPanierProduit(PanierProduit $panierProduit): static
    {
        if (!$this->panierProduits->contains($panierProduit)) {
            $this->panierProduits->add($panierProduit);
            $panierProduit->setPanier($this);
        }

        return $this;
    }

    public function removePanierProduit(PanierProduit $panierProduit): static
    {
        if ($this->panierProduits->removeElement($panierProduit)) {
            // set the owning side to null (unless already changed)
            if ($panierProduit->getPanier() === $this) {
                $panierProduit->setPanier(null);
            }
        }

        return $this;
    }
}
