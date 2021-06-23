<?php

namespace App\Utils\Mailer\DTO;

class MailerOptions
{
    /**
     * @var string
     */
    private $recipient;

    /**
     * @var string|null
     */
    private $cc;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $htmlTemplate;

    /**
     * @var array
     */
    private $context;

    /**
     * @var string
     */
    private $text;

    /**
     * @return string
     */
    public function getRecipient(): string
    {
        return $this->recipient;
    }

    /**
     * @param string $recipient
     */
    public function setRecipient(string $recipient): MailerOptions
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @return string
     */
    public function getCc(): ?string
    {
        return $this->cc;
    }

    /**
     * @param string $cc
     */
    public function setCc(string $cc): MailerOptions
    {
        $this->cc = $cc;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): MailerOptions
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getHtmlTemplate(): string
    {
        return $this->htmlTemplate;
    }

    /**
     * @param string $htmlTemplate
     */
    public function setHtmlTemplate(string $htmlTemplate): MailerOptions
    {
        $this->htmlTemplate = $htmlTemplate;
        return $this;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @param array $context
     */
    public function setContext(array $context): MailerOptions
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): MailerOptions
    {
        $this->text = $text;
        return $this;
    }
}