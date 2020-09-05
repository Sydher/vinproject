<?php

namespace App\Entity;

use App\Repository\WineRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=WineRepository::class)
 * @UniqueEntity(fields={"name"}, message="There is already a wine with this name")
 * @ORM\HasLifecycleCallbacks
 */
class Wine {

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
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="wines")
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $format;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Appellation::class, inversedBy="wines")
     */
    private $appellation;

    /**
     * @ORM\ManyToOne(targetEntity=Productor::class, inversedBy="wines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $productor;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vintage;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $price;

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

    public function getColor(): ?string {
        return $this->color;
    }

    public function setColor(string $color): self {
        $this->color = $color;
        return $this;
    }

    public function getYear(): ?string {
        return $this->year;
    }

    public function setYear(string $year): self {
        $this->year = $year;
        return $this;
    }

    public function getFormat(): ?string {
        return $this->format;
    }

    public function setFormat(string $format): self {
        $this->format = $format;
        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(string $description): self {
        $this->description = $description;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void {
        $this->setUpdatedAt(new \DateTime('now'));
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    public function getAppellation(): ?Appellation {
        return $this->appellation;
    }

    public function setAppellation(?Appellation $appellation): self {
        $this->appellation = $appellation;
        return $this;
    }

    public function getProductor(): ?Productor {
        return $this->productor;
    }

    public function setProductor(?Productor $productor): self {
        $this->productor = $productor;
        return $this;
    }

    public function getSlug(): ?string {
        return (new Slugify())->slugify($this->name);
    }

    public function getVintage(): ?string {
        return $this->vintage;
    }

    public function setVintage(?string $vintage): self {
        $this->vintage = $vintage;
        return $this;
    }

    public function getPrice(): ?string {
        return $this->price;
    }

    public function setPrice(string $price): self {
        $this->price = $price;
        return $this;
    }

}
