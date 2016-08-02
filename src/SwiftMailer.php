<?php declare(strict_types = 1);

namespace JDR\MailerSwiftMailerBridge;

use JDR\Mailer\EmailBuilder;
use JDR\Mailer\EmailType;
use JDR\Mailer\Mailer;
use JDR\MailerSwiftMailerBridge\Email\SwiftEmail;
use Swift_Mailer;

class SwiftMailer implements Mailer
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var EmailBuilder
     */
    private $builder;

    /**
     * Constructor.
     *
     * @param Swift_Mailer $mailer
     * @param EmailBuilder $builder
     */
    public function __construct(Swift_Mailer $mailer, EmailBuilder $builder)
    {
        $this->mailer = $mailer;
        $this->builder = $builder;
    }

    /**
     * Build and send a given type of email.
     *
     * @param EmailType $type
     */
    public function sendEmail(EmailType $type)
    {
        $builder = $this->builder;
        $type->buildEmail($builder);
        $email = $builder->build(new SwiftEmail());

        $this->mailer->send($email->getMessage());
    }
}
