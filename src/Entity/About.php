<?php

namespace App\Entity;

use App\Repository\AboutRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=AboutRepository::class)
 * @UniqueEntity(fields={"name"}, message="NameUnique")
 * @ORM\HasLifecycleCallbacks
 */
class About {

    use DateTimeTrait;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $value;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $textValue;

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

    public function getValue() {
        return $this->value;
    }

    public function setValue($value): self{
        $this->value = $value;
        return $this;
    }

    public function getTextValue(): ?string {
        return $this->textValue;
    }

    public function setTextValue(?string $textValue): self {
        $this->textValue = $textValue;
        return $this;
    }

}
