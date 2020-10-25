<?php

namespace App\Entity;

use App\Repository\AppellationRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=AppellationRepository::class)
 * @UniqueEntity(fields={"name"}, message="AppelationUnique")
 * @ORM\HasLifecycleCallbacks
 */
class Appellation {

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
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="appellations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $region;

    /**
     * @ORM\ManyToMany(targetEntity=Productor::class, mappedBy="appellations")
     */
    private $productors;

    /**
     * @ORM\OneToMany(targetEntity=Wine::class, mappedBy="appellation")
     */
    private $wines;

    public function __construct() {
        $this->productors = new ArrayCollection();
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

    public function getRegion(): ?Region {
        return $this->region;
    }

    public function setRegion(?Region $region): self {
        $this->region = $region;
        return $this;
    }

    /**
     * @return Collection|Productor[]
     */
    public function getProductors(): Collection {
        return $this->productors;
    }

    public function addProductor(Productor $productor): self {
        if (!$this->productors->contains($productor)) {
            $this->productors[] = $productor;
            $productor->addAppellation($this);
        }
        return $this;
    }

    public function removeProductor(Productor $productor): self {
        if ($this->productors->contains($productor)) {
            $this->productors->removeElement($productor);
            $productor->removeAppellation($this);
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
            $wine->setAppellation($this);
        }

        return $this;
    }

    public function removeWine(Wine $wine): self {
        if ($this->wines->contains($wine)) {
            $this->wines->removeElement($wine);
            // set the owning side to null (unless already changed)
            if ($wine->getAppellation() === $this) {
                $wine->setAppellation(null);
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
