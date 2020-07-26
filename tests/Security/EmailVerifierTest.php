<?php

namespace App\Tests\Security;

use App\Security\EmailVerifier;
use App\Tests\AbstractTest;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use PHPUnit\Framework\MockObject\MockObject;

class EmailVerifierTest extends AbstractTest {

    /* ***** Mocks ***** */

    /** @var VerifyEmailHelperInterface|MockObject */
    private $mockVerifyEmailHelper;

    /** @var MailerInterface|MockObject */
    private $mockMailer;

    /** @var TemplatedEmail */
    private $mockTemplatedEmail;

    /* ***** Initialisation ***** */

    /** @var EmailVerifier */
    private $emailVerifier;

    protected function setUp() {
        parent::setUp();
        $this->mockVerifyEmailHelper = $this->createMock(VerifyEmailHelperInterface::class);
        $this->mockMailer = $this->createMock(MailerInterface::class);

        $this->mockTemplatedEmail = $this->createMock(TemplatedEmail::class);
        $this->mockTemplatedEmail->method("getContext")->willReturn([]);

        $this->emailVerifier = new EmailVerifier($this->mockVerifyEmailHelper, $this->mockMailer, $this->mockEntityManager);
    }

    /* ***** Tests ***** */

    /**
     * @test sendEmailConfirmationFR
     */
    public function sendEmailConfirmationFRTest() {
        $mock = $this->getMockBuilder(EmailVerifier::class)
            ->setMethods(["sendEmailConfirmation"])
            ->disableOriginalConstructor()
            ->getMock();
        $mock->expects($this->once())->method("sendEmailConfirmation");
        $mock->sendEmailConfirmationFR($this->userUnVerified);
    }

    /**
     * @test sendEmailConfirmation
     */
    public function sendEmailConfirmationTest() {
        $this->mockVerifyEmailHelper->expects($this->once())
            ->method("generateSignature")
            ->willReturn(new VerifyEmailSignatureComponents(new \DateTime(), "uri_tu"));
        $this->mockTemplatedEmail->expects($this->once())->method("context");
        $this->mockMailer->expects($this->once())->method("send");
        $this->emailVerifier->sendEmailConfirmation("/route_tu", $this->userUnVerified, $this->mockTemplatedEmail);
    }

    /**
     * @test handleEmailConfirmation
     */
    public function handleEmailConfirmationTest() {
        $this->mockRequest->method("getUri")->willReturn("http://tu.fr");

        $this->mockVerifyEmailHelper->expects($this->once())->method("validateEmailConfirmation");
        $this->mockEntityManager->expects($this->once())->method("persist")->withAnyParameters();
        $this->mockEntityManager->expects($this->once())->method("flush");
        try {
            $this->emailVerifier->handleEmailConfirmation($this->mockRequest, $this->userUnVerified);
        } catch (VerifyEmailExceptionInterface $e) {
            $this->fail($e->getReason());
        }
        $this->assertTrue($this->userUnVerified->isVerified());
    }

}
