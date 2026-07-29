<?php

namespace App\Actions\Template;

use App\Actions\BaseAction;
use App\Models\Template;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CreateTemplate extends BaseAction
{
    protected array $validationRules = [
        'name' => 'required|string|max:255|unique:templates,name',
        'description' => 'nullable|string',
        'category' => 'nullable|string',
        'style' => 'nullable|string',
        'is_free' => 'nullable|boolean',
        'price' => 'nullable|numeric|min:0',
        'files' => 'nullable|array',
        'files.template' => 'required_with:files|file|mimes:php',
        'files.styles' => 'required_with:files|file|mimes:css',
        'files.scripts' => 'required_with:files|file|mimes:js',
        'structure' => 'nullable|array',
        'settings' => 'nullable|array',
        'features' => 'nullable|array',
        'tags' => 'nullable|array',
        'preview_image' => 'nullable|image|max:2048',
        'demo_url' => 'nullable|url',
        'documentation_url' => 'nullable|url',
    ];

    public function execute(array $data): Template
    {
        $this->data = $data;
        $this->validate($data);

        $this->beginTransaction();

        try {
            // Create template
            $template = Template::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => $data['description'] ?? null,
                'category' => $data['category'] ?? 'general',
                'style' => $data['style'] ?? 'modern',
                'is_free' => $data['is_free'] ?? true,
                'price' => $data['price'] ?? 0,
                'is_active' => $data['is_active'] ?? true,
                'is_recommended' => $data['is_recommended'] ?? false,
                'settings' => $data['settings'] ?? [],
                'features' => $data['features'] ?? [],
                'tags' => $data['tags'] ?? [],
                'demo_url' => $data['demo_url'] ?? null,
                'documentation_url' => $data['documentation_url'] ?? null,
            ]);

            // Upload template files
            if (isset($data['files'])) {
                $this->uploadTemplateFiles($template, $data['files']);
            }

            // Upload preview image
            if (isset($data['preview_image'])) {
                $this->uploadPreviewImage($template, $data['preview_image']);
            }

            // Create template structure
            if (isset($data['structure'])) {
                $this->createTemplateStructure($template, $data['structure']);
            }

            // Create template configuration
            $this->createTemplateConfig($template);

            $this->commit();

            return $template->fresh();

        } catch (\Exception $e) {
            $this->rollback();
            $this->logError('Failed to create template', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function uploadTemplateFiles(Template $template, array $files): void
    {
        $templatePath = resource_path("views/templates/{$template->slug}");
        
        // Create template directory
        if (!File::exists($templatePath)) {
            File::makeDirectory($templatePath, 0755, true);
        }

        // Upload each file
        foreach ($files as $name => $file) {
            if ($file instanceof \Illuminate\Http\UploadedFile) {
                $file->storeAs("templates/{$template->slug}", $name, 'public');
            }
        }

        // Create assets directory
        $assetsPath = public_path("templates/{$template->slug}");
        if (!File::exists($assetsPath)) {
            File::makeDirectory($assetsPath, 0755, true);
        }
    }

    protected function uploadPreviewImage(Template $template, $image): void
    {
        $path = $image->store("templates/{$template->slug}/preview", 'public');
        $template->update(['preview_image' => $path]);
    }

    protected function createTemplateStructure(Template $template, array $structure): void
    {
        $structurePath = resource_path("views/templates/{$template->slug}/structure.json");
        File::put($structurePath, json_encode($structure, JSON_PRETTY_PRINT));
    }

    protected function createTemplateConfig(Template $template): void
    {
        $configPath = resource_path("views/templates/{$template->slug}/config.php");
        
        $configContent = <<<PHP
<?php

return [
    'name' => '{$template->name}',
    'slug' => '{$template->slug}',
    'description' => '{$template->description}',
    'category' => '{$template->category}',
    'style' => '{$template->style}',
    'version' => '1.0.0',
    'author' => 'System',
    'settings' => [
        // Add template settings here
    ],
    'regions' => [
        'header',
        'content',
        'sidebar',
        'footer',
    ],
    'layouts' => [
        'default',
        'full-width',
        'with-sidebar',
    ],
];
PHP;

        File::put($configPath, $configContent);
    }
}