<?php

namespace App\Services\Website;

use App\Models\Section;
use App\Models\Page;

class SectionService
{
    public function createSection(Page $page, array $data): Section
    {
        $section = Section::create([
            'page_id' => $page->id,
            'type' => $data['type'],
            'title' => $data['title'] ?? null,
            'content' => $data['content'] ?? [],
            'settings' => $data['settings'] ?? [],
            'order' => $data['order'] ?? 0,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return $section;
    }

    public function updateSection(Section $section, array $data): Section
    {
        $section->update($data);
        return $section;
    }

    public function deleteSection(Section $section): bool
    {
        return $section->delete();
    }

    public function getPageSections(Page $page)
    {
        return $page->sections()->where('is_active', true)->orderBy('order')->get();
    }

    public function reorderSections(array $order): bool
    {
        foreach ($order as $id => $position) {
            Section::where('id', $id)->update(['order' => $position]);
        }
        return true;
    }

    public function duplicateSection(Section $section, Page $newPage): Section
    {
        $newSection = $section->replicate();
        $newSection->page_id = $newPage->id;
        $newSection->save();
        return $newSection;
    }

    public function toggleSection(Section $section): bool
    {
        $section->is_active = !$section->is_active;
        $section->save();
        return true;
    }

    public function getSectionTypes(): array
    {
        return [
            'hero' => 'Hero Section',
            'about' => 'About Section',
            'services' => 'Services Section',
            'portfolio' => 'Portfolio Section',
            'team' => 'Team Section',
            'testimonials' => 'Testimonials Section',
            'contact' => 'Contact Section',
            'faq' => 'FAQ Section',
            'features' => 'Features Section',
            'cta' => 'Call to Action',
            'pricing' => 'Pricing Section',
            'blog' => 'Blog Section',
            'gallery' => 'Gallery Section',
            'newsletter' => 'Newsletter Section',
            'custom' => 'Custom Section',
        ];
    }
}