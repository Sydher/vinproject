<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=RegionRepository::class)
 * @UniqueEntity(fields={"name"}, message="There is already a region with this name")
 * @ORM\HasLifecycleCallbacks
 */
class Region {

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
     * @ORM\OneToMany(targetEntity=Wine::class, mappedBy="region")
     */
    private $wines;

    /**
     * @ORM\OneToMany(targetEntity=Appellation::class, mappedBy="region")
     */
    private $appellations;

    public function __construct() {
        $this->wines = new ArrayCollection();
        $this->appellations = new ArrayCollection();
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
     * @return Collection|Wine[]
     */
    public function getWines(): Collection {
        return $this->wines;
    }

    public function addWine(Wine $wine): self {
        if (!$this->wines->contains($wine)) {
            $this->wines[] = $wine;
            $wine->setRegion($this);
        }
        return $this;
    }

    public function removeWine(Wine $wine): self {
        if ($this->wines->contains($wine)) {
            $this->wines->removeElement($wine);
            // set the owning side to null (unless already changed)
            if ($wine->getRegion() === $this) {
                $wine->setRegion(null);
            }
        }
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
            $appellation->setRegion($this);
        }

        return $this;
    }

    public function removeAppellation(Appellation $appellation): self {
        if ($this->appellations->contains($appellation)) {
            $this->appellations->removeElement($appellation);
            // set the owning side to null (unless already changed)
            if ($appellation->getRegion() === $this) {
                $appellation->setRegion(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string {
        return (new Slugify())->slugify($this->name);
    }

}
