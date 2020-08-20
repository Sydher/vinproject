<?php

namespace App\Tests\Entity;

use App\Entity\Post;
use App\Tests\AbstractTest;

class PostTest extends AbstractTest {

    /** @var Post */
    private $post;

    protected function setUp() {
        parent::setUp();
        $this->post = new Post();
    }

    /**
     * @test getSlug
     */
    public function getSlugTest() {
        $this->post->setTitle("Ceci est un titre !");
        $this->assertEquals("ceci-est-un-titre", $this->post->getSlug());
    }

}
