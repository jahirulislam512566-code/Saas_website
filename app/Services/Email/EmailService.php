<?php

namespace App\Services\Email;

use App\Services\Email\Mailers\SmtpMailer;
use App\Services\Email\Mailers\SendgridMailer;
use App\Services\Email\Mailers\MailgunMailer;
use App\Services\Email\Templates\BaseTemplate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

class EmailService
{
    protected array $mailers = [];
    protected string $defaultMailer;
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->defaultMailer = $config['default'] ?? config('mail.default', 'smtp');
        $this->initializeMailers($config['mailers'] ?? config('mail.mailers', []));
    }

    protected function initializeMailers(array $mailerConfigs): void
    {
        foreach ($mailerConfigs as $name => $config) {
            if (!$config['enabled'] ?? true) {
                continue;
            }

            $mailer = $this->createMailer($name, $config);
            if ($mailer) {
                $this->mailers[$name] = $mailer;
            }
        }
    }

    protected function createMailer(string $name, array $config): ?MailerInterface
    {
        return match ($name) {
            'smtp' => new SmtpMailer($config),
            'sendgrid' => new SendgridMailer($config),
            'mailgun' => new MailgunMailer($config),
            default => null,
        };
    }

    /**
     * Send an email.
     */
    public function send(BaseTemplate $template, ?string $mailerName = null): bool
    {
        $mailerName = $mailerName ?? $this->defaultMailer;
        $mailer = $this->getMailer($mailerName);

        if (!$mailer) {
            Log::error('Mailer not found', ['mailer' => $mailerName]);
            return false;
        }

        try {
            $result = $mailer->send($template);
            
            Log::info('Email sent', [
                'mailer' => $mailerName,
                'to' => $template->getTo(),
                'subject' => $template->getSubject(),
                'template' => get_class($template),
            ]);

            return $result;

        } catch (\Exception $e) {
            Log::error('Email sending failed', [
                'mailer' => $mailerName,
                'error' => $e->getMessage(),
                'to' => $template->getTo(),
                'subject' => $template->getSubject(),
            ]);

            return false;
        }
    }

    /**
     * Queue an email for sending.
     */
    public function queue(BaseTemplate $template, ?string $mailerName = null): bool
    {
        $mailerName = $mailerName ?? $this->defaultMailer;

        try {
            Queue::push(function () use ($template, $mailerName) {
                $this->send($template, $mailerName);
            });

            Log::info('Email queued', [
                'mailer' => $mailerName,
                'to' => $template->getTo(),
                'subject' => $template->getSubject(),
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Email queue failed', [
                'mailer' => $mailerName,
                'error' => $e->getMessage(),
                'to' => $template->getTo(),
            ]);

            return false;
        }
    }

    /**
     * Send email with retry logic.
     */
    public function sendWithRetry(BaseTemplate $template, int $maxRetries = 3): bool
    {
        $attempts = 0;
        $success = false;
        $lastError = null;

        while ($attempts < $maxRetries && !$success) {
            try {
                $attempts++;
                $success = $this->send($template);
                
                if (!$success) {
                    throw new \Exception('Failed to send email');
                }
                
            } catch (\Exception $e) {
                $lastError = $e->getMessage();
                Log::warning('Email send retry', [
                    'attempt' => $attempts,
                    'error' => $lastError,
                    'to' => $template->getTo(),
                ]);
                
                // Wait before retry
                if ($attempts < $maxRetries) {
                    sleep(pow(2, $attempts));
                }
            }
        }

        if (!$success) {
            Log::error('Email send failed after retries', [
                'max_retries' => $maxRetries,
                'error' => $lastError,
                'to' => $template->getTo(),
            ]);
        }

        return $success;
    }

    protected function getMailer(string $name): ?MailerInterface
    {
        return $this->mailers[$name] ?? null;
    }

    public function getAvailableMailers(): array
    {
        return array_keys($this->mailers);
    }

    public function getDefaultMailer(): string
    {
        return $this->defaultMailer;
    }
}