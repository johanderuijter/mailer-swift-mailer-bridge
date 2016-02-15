<?php declare(strict_types = 1);

namespace JDR\MailerSwiftMailerBridge\Email;

use JDR\Mailer\Email\Address;
use JDR\Mailer\Email\Email;
use JDR\Mailer\Email\Message;
use Swift_Message;

/**
 * Email.
 */
class SwiftEmail implements Email
{
    /**
     * @var Swift_Message
     */
    private $message;

    /**
     * Constructor.
     *
     * @param Swift_Message|null $message
     */
    public function __construct(Swift_Message $message = null)
    {
        $this->message = $message ?? Swift_Message::newInstance();
    }

    /**
     * Get message.
     *
     * @return Swift_Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the sender of this message.
     *
     * @param Address $address
     *
     * @return self
     */
    public function setSender(Address $address): Email
    {
        $this->message->setSender($address->getEmail(), $address->getName());

        return $this;
    }

    /**
     * Get the sender address for this message.
     *
     * @return Address
     */
    public function getSender(): Address
    {
    }

    /**
     * Set the Reply-To addresses.
     *
     * Any replies from the receiver will be sent to this address.
     *
     * @param Address[] $addresses
     *
     * @return self
     */
    public function setReplyTo(array $addresses): Email
    {
        $addresses = $this->transformAddressesToArray($addresses);
        $this->message->setReplyTo($addresses);

        return $this;
    }

    /**
     * Get the Reply-To addresses for this message.
     *
     * @return Address[]
     */
    public function getReplyTo(): array
    {
        $addresses = $this->message->getReplyTo();

        return $this->transformArrayToAddresses($addresses);
    }

    /**
     * Set the To addresses.
     *
     * Recipients set in this field will receive a copy of this message.
     *
     * @param Address[] $addresses
     *
     * @return self
     */
    public function setTo(array $addresses): Email
    {
        $addresses = $this->transformAddressesToArray($addresses);
        $this->message->setTo($addresses);

        return $this;
    }

    /**
     * Get the To addresses for this message.
     *
     * @return Address[]
     */
    public function getTo(): array
    {
        $addresses = $this->message->getTo();

        return $this->transformArrayToAddresses($addresses);
    }

    /**
     * Set the Cc addresses.
     *
     * Recipients set in this field will receive a 'carbon-copy' of this message.
     *
     * @param Address[] $addresses
     *
     * @return self
     */
    public function setCc(array $addresses): Email
    {
        $addresses = $this->transformAddressesToArray($addresses);
        $this->message->setCc($addresses);

        return $this;
    }

    /**
     * Get the Cc addresses for this message.
     *
     * @return Address[]
     */
    public function getCc(): array
    {
        $addresses = $this->message->getCc();

        return $this->transformArrayToAddresses($addresses);
    }

    /**
     * Set the Bcc addresses.
     *
     * Recipients set in this field will receive a 'blind-carbon-copy' of this message.
     *
     * @param Address[] $addresses
     *
     * @return self
     */
    public function setBcc(array $addresses): Email
    {
        $addresses = $this->transformAddressesToArray($addresses);
        $this->message->setBcc($addresses);

        return $this;
    }

    /**
     * Get the Bcc addresses for this message.
     *
     * @return Address[]
     */
    public function getBcc(): array
    {
        $addresses = $this->message->getBcc();

        return $this->transformArrayToAddresses($addresses);
    }

    /**
     * Set the subject of the message.
     *
     * @param string $subject
     *
     * @return self
     */
    public function setSubject($subject): Email
    {
        $this->message->setSubject($subject);

        return $this;
    }

    /**
     * Get the subject of the message.
     *
     * @return string
     */
    public function getSubject(): string
    {
        return $this->message->getSubject();
    }

    /**
     * Add message body to email.
     *
     * @param Message $message
     *
     * @return self
     */
    public function addMessage(Message $message): Email
    {
        if (null === $this->message->getBody()) {
            $this->message->setBody($message->getBody(), $message->getContentType());

            return $this;
        }

        $this->message->addPart($message->getBody(), $message->getContentType());

        return $this;
    }

    /**
     * Get all message body parts from the email.
     *
     * @return Message[]
     */
    public function getMessages(): array
    {
        $templates = [
            new Message($this->message->getBody(), $this->message->getContentType()),
        ];

        // TODO: Loop through parts

        return $templates;
    }

    /**
     * Transform addresses.
     *
     * Convert an array of Addresses to a format swift mailer can work with.
     *
     * @param Address[] $addresses
     *
     * @return array
     */
    private function transformAddressesToArray($addresses): array
    {
        $transformed = [];
        foreach ($addresses as $address) {
            if (null === $address->getName()) {
                $transformed[] = $address->getEmail();

                continue;
            }

            $transformed[$address->getEmail()] = $address->getName();
        }

        return $transformed;
    }

    /**
     * Transform addresses.
     *
     * Convert an array of Addresses to a format swift mailer can work with.
     *
     * @param Address[] $addresses
     *
     * @return array
     */
    private function transformArrayToAddresses($addresses): array
    {
        $transformed = [];
        foreach ($addresses as $email => $name) {
            $address = new Address($email, $name);
            $transformed[] = $address;
        }

        return $transformed;
    }
}
