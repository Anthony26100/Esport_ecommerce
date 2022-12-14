<?php

namespace App\Entity;

use App\Entity\Produits;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[ORM\ManyToMany(targetEntity: Produits::class, inversedBy: 'categories')]
    private Collection $produits;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['titre'])]
    private ?string $slug = null;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->titre;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * @return Collection<int, produits>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(produits $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
        }

        return $this;
    }

    public function removeProduit(produits $produit): self
    {
        $this->produits->removeElement($produit);

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
