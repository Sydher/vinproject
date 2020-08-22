<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @UniqueEntity(fields={"title"}, message="There is already a post with this title")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Post {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
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
     * @var File|null
     * @Assert\Image(
     *     mimeTypes = {"image/jpeg", "image/png"}
     * )
     * @Vich\UploadableField(mapping="post_image", fileNameProperty="imageName")
     */
    private $imageFile;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVisible = false;

    public function getId(): ?int {
        return $this->id;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(string $title): self {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string {
        return $this->content;
    }

    public function setContent(string $content): self {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?User {
        return $this->author;
    }

    public function setAuthor(?User $author): self {
        $this->author = $author;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return Post
     */
    public function setDescription($description) {
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

    public function getSlug(): ?string {
        return (new Slugify())->slugify($this->title);
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     * @return Post
     */
    public function setImageFile(?File $imageFile): Post {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime('now'));
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageName() {
        return $this->imageName;
    }

    /**
     * @param mixed $imageName
     * @return Post
     */
    public function setImageName($imageName): self {
        $this->imageName = $imageName;
        return $this;
    }

    public function getIsVisible(): ?bool {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): self {
        $this->isVisible = $isVisible;

        return $this;
    }

}
