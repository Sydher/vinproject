<?php

namespace App\Entity;

class SearchProduct {

    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var string
     */
    public $name = '';

    /**
     * @var null|Appellation[]
     */
    public $appellation;

    /**
     * @var null|Productor[]
     */
    public $productor;

    /**
     * @var null|integer
     */
    public $min;

    /**
     * @var null|integer
     */
    public $max;

}