<?php

namespace App\Services;

use App\Models\Template;
use App\Models\Website;
use Illuminate\Support\Facades\File;

class TemplateService
{
    public function getAvailableTemplates(): array
    {
        $templates = [];
        $templatePath = resource_path('views/templates');
        
        if (File::exists($templatePath)) {
            $directories = File::directories($templatePath);
            
            foreach ($directories as $directory) {
                $name = basename($directory);
                $config = $this->getTemplateConfig($name);
                
                if ($config) {
                    $templates[$name] = $config;
                }
            }
        }
        
        return $templates;
    }

    public function getTemplateConfig(string $template): ?array
    {
        $configPath = resource_path("views/templates/{$template}/config.php");
        
        if (File::exists($configPath)) {
            return include $configPath;
        }
        
        return null;
    }

    public function applyTemplate(Website $website, string $template): Website
    {
        $website->update(['theme' => $template]);
        $this->createTemplateStructure($website, $template);
        
        return $website;
    }

    private function createTemplateStructure(Website $website, string $template): void
    {
        $templatePath = resource_path("views/templates/{$template}/structure.json");
        
        if (File::exists($templatePath)) {
            $structure = json_decode(File::get($templatePath), true);
            
            if (isset($structure['pages'])) {
                foreach ($structure['pages'] as $pageData) {
                    // Create pages with sections based on template structure
                }
            }
        }
    }

    public function installTemplate(string $template): bool
    {
        // Logic to install a new template
        return true;
    }

    public function uninstallTemplate(string $template): bool
    {
        // Logic to uninstall a template
        return true;
    }

    public function getTemplateAssets(string $template): array
    {
        $assets = [];
        $assetPath = public_path("templates/{$template}");
        
        if (File::exists($assetPath)) {
            $assets['css'] = File::files("{$assetPath}/css");
            $assets['js'] = File::files("{$assetPath}/js");
            $assets['images'] = File::files("{$assetPath}/images");
        }
        
        return $assets;
    }

    public function getTemplateSettings(string $template): array
    {
        $config = $this->getTemplateConfig($template);
        return $config['settings'] ?? [];
    }
}