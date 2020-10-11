<?php

namespace App\Entity;

use App\Repository\WineRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=WineRepository::class)
 * @UniqueEntity(fields={"name"}, message="There is already a wine with this name")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Wine implements Product {

    use DateTimeTrait, ProductTrait;

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
     * @var File|null
     * @Assert\Image(
     *     mimeTypes = {"image/jpeg", "image/png"}
     * )
     * @Vich\UploadableField(mapping="wine_image", fileNameProperty="imageName")
     */
    private $imageFile;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $degree;

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

    /**
     * @return File|null
     */
    public function getImageFile(): ?File {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     * @return Wine
     */
    public function setImageFile(?File $imageFile): Wine {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime('now'));
        }
        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageName(): ?string {
        return $this->imageName;
    }

    /**
     * @param string|null $imageName
     * @return Wine
     */
    public function setImageName(?string $imageName): Wine {
        $this->imageName = $imageName;
        return $this;
    }

    public function getDegree(): ?string {
        return $this->degree;
    }

    public function setDegree(string $degree): self {
        $this->degree = $degree;
        return $this;
    }

}
