<?php

namespace App\Services\Email\Mailers;

use App\Services\Email\Templates\BaseTemplate;

interface MailerInterface
{
    public function send(BaseTemplate $template): bool;
    public function getConfig(): array;
    public function getMailerName(): string;
}