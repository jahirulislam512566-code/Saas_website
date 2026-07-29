<?php

namespace App\Services\Email\Templates;

abstract class BaseTemplate
{
    protected array $data;
    protected string $to;
    protected ?string $subject;
    protected array $attachments = [];
    protected array $cc = [];
    protected array $bcc = [];

    public function __construct(string $to, array $data = [], ?string $subject = null)
    {
        $this->to = $to;
        $this->data = $data;
        $this->subject = $subject ?? $this->getDefaultSubject();
    }

    abstract protected function getDefaultSubject(): string;
    abstract protected function getView(): string;
    abstract protected function getViewData(): array;

    public function getTo(): string
    {
        return $this->to;
    }

    public function getSubject(): string
    {
        return $this->subject ?? $this->getDefaultSubject();
    }

    public function getViewContent(): string
    {
        $data = $this->getViewData();
        return view($this->getView(), $data)->render();
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function addAttachment(string $path, ?string $name = null): self
    {
        $this->attachments[] = [
            'path' => $path,
            'name' => $name ?? basename($path),
        ];
        return $this;
    }

    public function getCc(): array
    {
        return $this->cc;
    }

    public function addCc(string $email): self
    {
        $this->cc[] = $email;
        return $this;
    }

    public function getBcc(): array
    {
        return $this->bcc;
    }

    public function addBcc(string $email): self
    {
        $this->bcc[] = $email;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'to' => $this->to,
            'subject' => $this->getSubject(),
            'content' => $this->getViewContent(),
            'cc' => $this->cc,
            'bcc' => $this->bcc,
            'attachments' => $this->attachments,
            'data' => $this->data,
        ];
    }
}