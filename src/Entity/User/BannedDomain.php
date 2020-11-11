<?php

namespace App\Entity\User;

use App\Entity\DateTimeTrait;
use App\Repository\User\BannedDomainRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=BannedDomainRepository::class)
 * @UniqueEntity(fields={"name"}, message="NameUnique")
 * @ORM\HasLifecycleCallbacks
 */
class BannedDomain {

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

    public function __toString() {
        return $this->getName();
    }

}
