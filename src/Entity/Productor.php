<?php

namespace App\Entity;

use App\Repository\ProductorRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ProductorRepository::class)
 * @UniqueEntity(fields={"name"}, message="ProductorUnique")
 * @ORM\HasLifecycleCallbacks
 */
class Productor {

    use DateTimeTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Appellation::class, inversedBy="productors")
     */
    private $appellations;

    /**
     * @ORM\OneToMany(targetEntity=Wine::class, mappedBy="productor")
     */
    private $wines;

    public function __construct() {
        $this->appellations = new ArrayCollection();
        $this->wines = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection|Appellation[]
     */
    public function getAppellations(): Collection {
        return $this->appellations;
    }

    public function addAppellation(Appellation $appellation): self {
        if (!$this->appellations->contains($appellation)) {
            $this->appellations[] = $appellation;
        }
        return $this;
    }

    public function removeAppellation(Appellation $appellation): self {
        if ($this->appellations->contains($appellation)) {
            $this->appellations->removeElement($appellation);
        }
        return $this;
    }

    /**
     * @return Collection|Wine[]
     */
    public function getWines(): Collection {
        return $this->wines;
    }

    public function addWine(Wine $wine): self {
        if (!$this->wines->contains($wine)) {
            $this->wines[] = $wine;
            $wine->setProductor($this);
        }

        return $this;
    }

    public function removeWine(Wine $wine): self {
        if ($this->wines->contains($wine)) {
            $this->wines->removeElement($wine);
            // set the owning side to null (unless already changed)
            if ($wine->getProductor() === $this) {
                $wine->setProductor(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string {
        return (new Slugify())->slugify($this->name);
    }

    public function __toString() {
        return $this->name;
    }

}
