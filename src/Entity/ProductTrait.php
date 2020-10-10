<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait ProductTrait {

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @return mixed
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @param mixed $price
     * @return ProductTrait
     */
    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStock() {
        return $this->stock;
    }

    /**
     * @param mixed $stock
     * @return ProductTrait
     */
    public function setStock($stock) {
        $this->stock = $stock;
        return $this;
    }

}
