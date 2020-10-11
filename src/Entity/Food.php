<?php

namespace App\Entity;

use App\Repository\FoodRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=FoodRepository::class)
 * @UniqueEntity(fields={"name"}, message="ProductNameUnique")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Food implements Product {

    use DateTimeTrait, ProductTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var File|null
     * @Assert\Image(
     *     mimeTypes = {"image/jpeg", "image/png"}
     * )
     * @Vich\UploadableField(mapping="food_image", fileNameProperty="imageName")
     */
    private $imageFile;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageName;

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

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(string $description): self {
        $this->description = $description;
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
     * @return Food
     */
    public function setImageFile(?File $imageFile): Food {
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
     * @return Food
     */
    public function setImageName(?string $imageName): Food {
        $this->imageName = $imageName;
        return $this;
    }

    public function getSlug(): ?string {
        return (new Slugify())->slugify($this->name);
    }

}
