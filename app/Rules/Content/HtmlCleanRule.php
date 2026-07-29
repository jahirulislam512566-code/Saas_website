<?php

namespace App\Rules\Content;

use App\Rules\BaseRule;

class HtmlCleanRule extends BaseRule
{
    protected array $allowedTags;
    protected bool $stripScripts;
    protected bool $stripXSS;

    public function __construct(
        array $allowedTags = ['p', 'br', 'strong', 'em', 'u', 'h1', 'h2', 'h3', 'ul', 'ol', 'li', 'a'],
        bool $stripScripts = true,
        bool $stripXSS = true
    ) {
        $this->allowedTags = $allowedTags;
        $this->stripScripts = $stripScripts;
        $this->stripXSS = $stripXSS;
    }

    public function passes($attribute, $value): bool
    {
        // Strip scripts if required
        if ($this->stripScripts) {
            $value = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $value);
        }

        // Strip XSS if required
        if ($this->stripXSS) {
            $value = $this->stripXSS($value);
        }

        // Check for malicious content
        $malicious = [
            'javascript:',
            'data:',
            'vbscript:',
            'onclick',
            'onload',
            'onerror',
            'onmouseover',
        ];

        foreach ($malicious as $pattern) {
            if (stripos($value, $pattern) !== false) {
                return false;
            }
        }

        // Validate HTML structure
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($value);
        $errors = libxml_get_errors();
        libxml_clear_errors();

        if (!empty($errors)) {
            return false;
        }

        // Check for allowed tags
        $tags = $this->getAllTags($value);
        foreach ($tags as $tag) {
            if (!in_array($tag, $this->allowedTags)) {
                return false;
            }
        }

        return true;
    }

    protected function stripXSS(string $value): string
    {
        // Remove tags
        $value = preg_replace('/<[^>]*>/', '', $value);

        // Remove scripts
        $value = preg_replace('/javascript:/i', '', $value);

        return $value;
    }

    protected function getAllTags(string $html): array
    {
        preg_match_all('/<([a-z]+)[^>]*>/i', $html, $matches);
        return array_map('strtolower', $matches[1]);
    }

    public function message(): string
    {
        return 'The HTML content contains invalid or unsafe elements.';
    }

    /**
     * Sanitize HTML content
     */
    public function sanitize(string $html): string
    {
        $html = strip_tags($html, '<' . implode('><', $this->allowedTags) . '>');
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
        $html = $this->stripXSS($html);
        return $html;
    }
}