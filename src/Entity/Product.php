<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(
     * message="Veuillez entrer un nom !"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $published_at;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @Assert\Positive(
     * message="Le prix doit être positif !"
     * )
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $status;

    const PUBLIE = 'publié';
    const BROUILLON = 'brouillon';

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="product",cascade={"persist"})
     * @Assert\NotNull(
     * message="Vous devez choisir une catégorie !"
     * )
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commande", mappedBy="product",cascade={"remove"})
     */
    private $commandes;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $ref;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Image(
     * maxSize = "1024k",
     * maxSizeMessage="Le fichier est trop volumineux 1Mo maximum",
     * uploadIniSizeErrorMessage="Le fichier est trop volumineux 1Mo maximum",
     * uploadExtensionErrorMessage="L'extension choisi ne correspond pas aux formats supportés",
     * uploadFormSizeErrorMessage="Le fichier est trop volumineux 1Mo maximum",
     * )
     */
    private $image;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $size = [];


    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->published_at;
    }

    public function setPublishedAt(\DateTimeInterface $published_at): self
    {
        $this->published_at = $published_at;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        if (!in_array($status, [
            self::PUBLIE, 
            self::BROUILLON])){
            throw new \InvalidArgumentException("Invalid status");
    }
        $this->status = $status;
        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|Commande[]
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setProduct($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->contains($commande)) {
            $this->commandes->removeElement($commande);
            // set the owning side to null (unless already changed)
            if ($commande->getProduct() === $this) {
                $commande->setProduct(null);
            }
        }

        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(?string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getSize(): ?array
    {
        return $this->size;
    }

    public function setSize(?array $size): self
    {
        $this->size = $size;

        return $this;
    }

}
