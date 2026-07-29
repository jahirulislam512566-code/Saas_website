<?php

namespace App\Services\AI;

use App\Models\AI\ContentGeneration;
use App\Models\AI\Recommendation;
use Illuminate\Support\Facades\Http;

class AIService
{
    private $apiKey;
    private $apiUrl;

    public function __construct()
    {
        $this->apiKey = env('AI_API_KEY');
        $this->apiUrl = env('AI_API_URL');
    }

    public function generateContent(array $params): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl . '/generate', [
            'prompt' => $params['prompt'],
            'type' => $params['type'] ?? 'general',
            'length' => $params['length'] ?? 'medium',
            'tone' => $params['tone'] ?? 'professional',
            'keywords' => $params['keywords'] ?? [],
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            ContentGeneration::create([
                'user_id' => $params['user_id'] ?? null,
                'prompt' => $params['prompt'],
                'type' => $params['type'] ?? 'general',
                'generated_text' => $data['text'],
                'metadata' => $data['metadata'] ?? [],
            ]);

            return $data;
        }

        throw new \Exception('Failed to generate content: ' . $response->body());
    }

    public function generateBlogPost(array $params): array
    {
        $prompt = "Write a blog post about {$params['topic']}.";
        
        if (isset($params['keywords'])) {
            $prompt .= " Include these keywords: " . implode(', ', $params['keywords']);
        }
        
        if (isset($params['tone'])) {
            $prompt .= " Use a {$params['tone']} tone.";
        }
        
        return $this->generateContent([
            'prompt' => $prompt,
            'type' => 'blog_post',
            'length' => 'long',
            'tone' => $params['tone'] ?? 'professional',
            'user_id' => $params['user_id'] ?? null,
        ]);
    }

    public function generateProductDescription(array $params): array
    {
        $prompt = "Write a product description for {$params['product_name']}.";
        
        if (isset($params['features'])) {
            $prompt .= " Features include: " . implode(', ', $params['features']);
        }
        
        if (isset($params['target_audience'])) {
            $prompt .= " Target audience: {$params['target_audience']}.";
        }
        
        return $this->generateContent([
            'prompt' => $prompt,
            'type' => 'product_description',
            'length' => 'medium',
            'tone' => 'persuasive',
            'user_id' => $params['user_id'] ?? null,
        ]);
    }

    public function generateSEOContent(array $params): array
    {
        $prompt = "Write SEO-optimized content about {$params['topic']}.";
        $prompt .= " Target keywords: " . implode(', ', $params['keywords']);
        $prompt .= " Target audience: {$params['target_audience']}";
        
        return $this->generateContent([
            'prompt' => $prompt,
            'type' => 'seo_content',
            'length' => 'long',
            'tone' => 'informative',
            'keywords' => $params['keywords'],
            'user_id' => $params['user_id'] ?? null,
        ]);
    }

    public function generateSocialMediaPost(array $params): array
    {
        $platforms = ['Twitter', 'Facebook', 'LinkedIn', 'Instagram'];
        $prompt = "Write a social media post for {$params['platform']} about {$params['topic']}.";
        
        if (isset($params['hashtags'])) {
            $prompt .= " Include hashtags: " . implode(', ', $params['hashtags']);
        }
        
        $result = $this->generateContent([
            'prompt' => $prompt,
            'type' => 'social_media',
            'length' => 'short',
            'tone' => 'engaging',
            'user_id' => $params['user_id'] ?? null,
        ]);

        // Add platform-specific formatting
        $result['platform_specific'] = $this->formatForPlatform($result['text'], $params['platform']);
        
        return $result;
    }

    public function generateEmailContent(array $params): array
    {
        $prompt = "Write an email";
        
        if (isset($params['type']) && $params['type'] === 'marketing') {
            $prompt .= " marketing campaign for {$params['product']}.";
        } elseif (isset($params['type']) && $params['type'] === 'newsletter') {
            $prompt .= " newsletter with topics: " . implode(', ', $params['topics']);
        } else {
            $prompt .= " for {$params['subject']}.";
        }
        
        return $this->generateContent([
            'prompt' => $prompt,
            'type' => 'email',
            'length' => 'medium',
            'tone' => $params['tone'] ?? 'professional',
            'user_id' => $params['user_id'] ?? null,
        ]);
    }

    public function getAISuggestions(string $content): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post($this->apiUrl . '/analyze', [
            'content' => $content,
            'analysis_type' => 'suggestions',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            Recommendation::create([
                'content' => $content,
                'suggestions' => $data['suggestions'],
                'metadata' => $data['metadata'] ?? [],
            ]);

            return $data['suggestions'];
        }

        return [];
    }

    public function getContentImprovements(array $params): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post($this->apiUrl . '/improve', [
            'content' => $params['content'],
            'improvement_type' => $params['type'] ?? 'general',
            'target_audience' => $params['target_audience'] ?? null,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }

    public function getContentIdeas(string $topic): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post($this->apiUrl . '/ideas', [
            'topic' => $topic,
            'number_of_ideas' => 5,
        ]);

        if ($response->successful()) {
            return $response->json()['ideas'] ?? [];
        }

        return [];
    }

    public function translateContent(array $params): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post($this->apiUrl . '/translate', [
            'content' => $params['content'],
            'target_language' => $params['target_language'],
            'source_language' => $params['source_language'] ?? 'en',
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Translation failed: ' . $response->body());
    }

    public function checkGrammar(string $content): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post($this->apiUrl . '/grammar-check', [
            'content' => $content,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }

    public function generateFAQ(array $params): array
    {
        $prompt = "Generate FAQ about {$params['topic']}.";
        
        if (isset($params['questions'])) {
            $prompt .= " Include these questions: " . implode(', ', $params['questions']);
        }
        
        return $this->generateContent([
            'prompt' => $prompt,
            'type' => 'faq',
            'length' => 'medium',
            'user_id' => $params['user_id'] ?? null,
        ]);
    }

    public function getKeywordSuggestions(string $topic): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post($this->apiUrl . '/keywords', [
            'topic' => $topic,
            'number_of_keywords' => 10,
        ]);

        if ($response->successful()) {
            return $response->json()['keywords'] ?? [];
        }

        return [];
    }

    private function formatForPlatform(string $content, string $platform): string
    {
        return match($platform) {
            'Twitter' => substr($content, 0, 280),
            'Instagram' => $content . "\n\n#hashtags #gohere",
            'Facebook' => $content . "\n\nFollow us for more!",
            'LinkedIn' => $content . "\n\n#professional #networking",
            default => $content,
        };
    }

    public function generateContentIdeas($website): array
    {
        $websiteData = [
            'name' => $website->name,
            'niche' => $website->niche,
            'description' => $website->description,
        ];

        return $this->getContentIdeas($websiteData['niche']);
    }
}