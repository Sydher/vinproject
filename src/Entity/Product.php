<?php

namespace App\Entity;

interface Product {

    /**
     * @return mixed
     */
    public function getPrice();

    /**
     * @param mixed $price
     * @return ProductTrait
     */
    public function setPrice($price);

    /**
     * @return mixed
     */
    public function getStock();

    /**
     * @param mixed $stock
     * @return ProductTrait
     */
    public function setStock($stock);

}