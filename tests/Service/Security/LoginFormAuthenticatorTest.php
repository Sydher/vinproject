<?php

namespace App\Tests\Service\Security;

use App\Repository\UserRepository;
use App\Service\Security\LoginFormAuthenticator;
use App\Tests\AbstractTest;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class LoginFormAuthenticatorTest extends AbstractTest {

    /* ***** Mocks ***** */

    /** @var UrlGeneratorInterface|MockObject */
    private $mockUrlGenerator;

    /** @var CsrfTokenManagerInterface|MockObject */
    private $mockCsrfTokenManager;

    /** @var UserPasswordEncoderInterface|MockObject */
    private $mockPasswordEncoder;

    /** @var SessionInterface|MockObject */
    private $mockSession;

    /** @var UserProviderInterface|MockObject */
    private $mockUserProvider;

    /** @var UserRepository|MockObject */
    private $mockUserRepository;

    /** @var TokenInterface|MockObject */
    private $mockToken;

    /* ***** Initialisation ***** */

    /** @var LoginFormAuthenticator */
    private $loginFormAuthenticator;

    protected function setUp() {
        parent::setUp();
        $this->mockUrlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->mockCsrfTokenManager = $this->createMock(CsrfTokenManagerInterface::class);
        $this->mockPasswordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $this->mockSession = $this->createMock(SessionInterface::class);
        $this->mockUserProvider = $this->createMock(UserProviderInterface::class);
        $this->mockUserRepository = $this->createMock(UserRepository::class);
        $this->mockToken = $this->createMock(TokenInterface::class);

        $this->loginFormAuthenticator = new LoginFormAuthenticator($this->mockEntityManager,
            $this->mockUrlGenerator, $this->mockCsrfTokenManager, $this->mockPasswordEncoder);
    }

    /* ***** Tests ***** */

    /**
     * @test supports
     * @case Route et méthode OK
     */
    public function supportsCaseOkTest() {
        $this->mockRequest->attributes = new ParameterBag();
        $this->mockRequest->attributes->add(array('_route' => 'login'));
        $this->mockRequest->method("isMethod")->willReturn(true);
        $this->assertTrue($this->loginFormAuthenticator->supports($this->mockRequest));
    }

    /**
     * @test supports
     * @case Route OK, méthode KO
     */
    public function supportsCaseKoWrongMethodTest() {
        $this->mockRequest->attributes = new ParameterBag();
        $this->mockRequest->attributes->add(array('_route' => 'login'));
        $this->mockRequest->method("isMethod")->willReturn(false);
        $this->assertFalse($this->loginFormAuthenticator->supports($this->mockRequest));
    }

    /**
     * @test supports
     * @case Route KO, méthode OK
     */
    public function supportsCaseKoWrongRouteTest() {
        $this->mockRequest->attributes = new ParameterBag();
        $this->mockRequest->attributes->add(array('_route' => 'connexion'));
        $this->mockRequest->method("isMethod")->willReturn(true);
        $this->assertFalse($this->loginFormAuthenticator->supports($this->mockRequest));
    }

    /**
     * @test supports
     * @case Route et méthode KO
     */
    public function supportsCaseKoTest() {
        $this->mockRequest->attributes = new ParameterBag();
        $this->mockRequest->attributes->add(array('_route' => 'connexion'));
        $this->mockRequest->method("isMethod")->willReturn(false);
        $this->assertFalse($this->loginFormAuthenticator->supports($this->mockRequest));
    }

    /**
     * @test getCredentials
     */
    public function getCredentialsTest() {
        $this->mockRequest->request = new ParameterBag();
        $this->mockRequest->request->add(array('email' => 'michel@vin.fr'));
        $this->mockRequest->request->add(array('password' => 'abc123'));
        $this->mockRequest->request->add(array('_csrf_token' => 'dfhig;j5ufd'));
        $this->mockRequest->method("getSession")
            ->willReturn($this->mockSession);
        $resultat = $this->loginFormAuthenticator->getCredentials($this->mockRequest);
        $this->assertNotNull($resultat);
        $this->assertEquals("michel@vin.fr", $resultat["email"]);
        $this->assertEquals("abc123", $resultat["password"]);
        $this->assertEquals("dfhig;j5ufd", $resultat["csrf_token"]);
    }

    /**
     * @test getUser
     * @case Cas OK
     */
    public function getUserCaseOkTest() {
        $credentials = [
            "email" => "michel@vin.fr",
            "csrf_token" => "dfhig;j5ufd"
        ];
        $this->mockCsrfTokenManager
            ->expects($this->once())
            ->method("isTokenValid")
            ->willReturn(true);
        $this->mockUserRepository
            ->expects($this->once())
            ->method("findOneBy")
            ->willReturn($this->userMichel);
        $this->mockEntityManager
            ->expects($this->once())
            ->method("getRepository")
            ->willReturn($this->mockUserRepository);
        $resultat = $this->loginFormAuthenticator->getUser($credentials, $this->mockUserProvider);
        $this->assertEquals($this->userMichel, $resultat);
    }

    /**
     * @test getUser
     * @case Cas token invalide
     */
    public function getUserCaseTokenInvalidTest() {
        $this->expectException(InvalidCsrfTokenException::class);
        $credentials = [
            "email" => "michel@vin.fr",
            "csrf_token" => "dfhig;j5ufd"
        ];
        $this->mockCsrfTokenManager
            ->expects($this->once())
            ->method("isTokenValid")
            ->willReturn(false);
        $this->mockUserRepository
            ->expects($this->never())
            ->method("findOneBy");
        $this->mockEntityManager
            ->expects($this->never())
            ->method("getRepository");
        $resultat = $this->loginFormAuthenticator->getUser($credentials, $this->mockUserProvider);
        $this->assertNull($resultat);
    }

    /**
     * @test getUser
     * @case Cas utilisateur non trouvé
     */
    public function getUserCaseUserNotFoundTest() {
        $this->expectException(CustomUserMessageAuthenticationException::class);
        $credentials = [
            "email" => "michel@vin.fr",
            "csrf_token" => "dfhig;j5ufd"
        ];
        $this->mockCsrfTokenManager
            ->expects($this->once())
            ->method("isTokenValid")
            ->willReturn(true);
        $this->mockUserRepository
            ->expects($this->once())
            ->method("findOneBy")
            ->willReturn(null);
        $this->mockEntityManager
            ->expects($this->once())
            ->method("getRepository")
            ->willReturn($this->mockUserRepository);
        $resultat = $this->loginFormAuthenticator->getUser($credentials, $this->mockUserProvider);
        $this->assertNull($resultat);
    }

    /**
     * @test checkCredentials
     * @case Cas OK
     */
    public function checkCredentialsCaseOkTest() {
        $credentials = [
            "email" => "michel@vin.fr",
            "password" => "abc123",
            "csrf_token" => "dfhig;j5ufd"
        ];
        $this->mockPasswordEncoder
            ->expects($this->once())
            ->method("isPasswordValid")
            ->willReturn(true);
        $this->assertTrue(
            $this->loginFormAuthenticator->checkCredentials($credentials, $this->userMichel));
    }

    /**
     * @test checkCredentials
     * @case Cas KO
     */
    public function checkCredentialsCaseKoTest() {
        $credentials = [
            "email" => "michel@vin.fr",
            "password" => "abc123",
            "csrf_token" => "dfhig;j5ufd"
        ];
        $this->mockPasswordEncoder
            ->expects($this->once())
            ->method("isPasswordValid")
            ->willReturn(false);
        $this->assertFalse(
            $this->loginFormAuthenticator->checkCredentials($credentials, $this->userMichel));
    }

    /**
     * @test getPassword
     */
    public function getPasswordTest() {
        $credentials = [
            "email" => "michel@vin.fr",
            "password" => "abc123",
            "csrf_token" => "dfhig;j5ufd"
        ];
        $this->assertEquals("abc123", $this->loginFormAuthenticator->getPassword($credentials));
    }

    /**
     * @test onAuthenticationSuccess
     * @case Cas OK
     */
    public function onAuthenticationSuccessCaseOkTest() {
        $this->mockRequest
            ->expects($this->once())
            ->method("getSession")
            ->willReturn($this->mockSession);
        $this->mockUrlGenerator
            ->expects($this->once())
            ->method("generate")
            ->willReturn("http://tu.fr/ok");
        $resultat = $this->loginFormAuthenticator->onAuthenticationSuccess(
            $this->mockRequest, $this->mockToken, "providerKey");
        $this->assertEquals("http://tu.fr/ok", $resultat->getTargetUrl());
    }

}
