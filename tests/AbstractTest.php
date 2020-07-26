<?php

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractTest extends TestCase {

    /* ***** Mocks ***** */

    /** @var EntityManagerInterface|MockObject */
    protected $mockEntityManager;

    /** @var Request|MockObject */
    protected $mockRequest;

    /* ***** Entités ***** */

    /** @var User */
    protected $userMichel;

    /** @var User */
    protected $userUnVerified;

    /* ***** Initialisation ***** */

    protected function setUp() {
        parent::setUp();
        $this->initMocks();
        $this->initUsers();
    }

    private function initMocks() {
        $this->mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $this->mockRequest = $this->createMock(Request::class);
    }

    private function initUsers() {
        $this->userMichel = new User();
        $this->userMichel->setId(1);
        $this->userMichel->setEmail("michel@michel.fr");
        $this->userMichel->setUsername("Michel");
        $this->userMichel->setIsVerified(true);

        $this->userUnVerified = new User();
        $this->userUnVerified->setId(1);
        $this->userUnVerified->setEmail("un@verified.fr");
        $this->userUnVerified->setUsername("Unverified");
        $this->userUnVerified->setIsVerified(false);
    }

    /* ***** Utilitaires ***** */

    // Empty

}
