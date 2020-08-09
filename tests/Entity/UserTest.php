<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Tests\AbstractTest;

class UserTest extends AbstractTest {

    /** @var User */
    private $user;

    protected function setUp() {
        parent::setUp();
        $this->user = new User();
    }

    /**
     * @test isAdmin
     * @case L'utilisateur n'a aucun rôle
     */
    public function isAdminCasNoRoleTest() {
        $this->user->setRoles([]);
        $this->assertFalse($this->user->isAdmin());
    }

    /**
     * @test isAdmin
     * @case L'utilisateur n'est pas admin
     */
    public function isAdminCasNotAdminTest() {
        $this->user->setRoles(["ROLE_INEXISTANT"]);
        $this->assertFalse($this->user->isAdmin());
    }

    /**
     * @test isAdmin
     * @case L'utilisateur a le rôle ROLE_ADMIN
     */
    public function isAdminCasAdminTest() {
        $this->user->setRoles(["ROLE_USER", "ROLE_ADMIN"]);
        $this->assertTrue($this->user->isAdmin());
    }

    /**
     * @test isAdmin
     * @case L'utilisateur a le rôle ROLE_SUPER_ADMIN
     */
    public function isAdminCasSuperAdminTest() {
        $this->user->setRoles(["ROLE_SUPER_ADMIN"]);
        $this->assertTrue($this->user->isAdmin());
    }

    /**
     * @test isSuperAdmin
     * @case L'utilisateur n'est pas admin
     */
    public function isSuperAdminCasNotAdminTest() {
        $this->user->setRoles(["ROLE_INEXISTANT"]);
        $this->assertFalse($this->user->isSuperAdmin());
    }

    /**
     * @test isSuperAdmin
     * @case L'utilisateur a le rôle ROLE_ADMIN
     */
    public function isSuperAdminCasAdminTest() {
        $this->user->setRoles(["ROLE_USER", "ROLE_ADMIN"]);
        $this->assertFalse($this->user->isSuperAdmin());
    }

    /**
     * @test isSuperAdmin
     * @case L'utilisateur a le rôle ROLE_SUPER_ADMIN
     */
    public function isSuperAdminCasSuperAdminTest() {
        $this->user->setRoles(["ROLE_SUPER_ADMIN"]);
        $this->assertTrue($this->user->isSuperAdmin());
    }

}
