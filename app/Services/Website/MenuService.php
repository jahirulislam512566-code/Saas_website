<?php

namespace App\Services\Website;

use App\Models\Menu;
use App\Models\Website;

class MenuService
{
    public function createMenu(Website $website, array $data): Menu
    {
        $menu = Menu::create([
            'website_id' => $website->id,
            'name' => $data['name'],
            'location' => $data['location'] ?? 'primary',
            'items' => $data['items'] ?? [],
            'is_active' => $data['is_active'] ?? true,
        ]);

        return $menu;
    }

    public function updateMenu(Menu $menu, array $data): Menu
    {
        $menu->update($data);
        return $menu;
    }

    public function deleteMenu(Menu $menu): bool
    {
        return $menu->delete();
    }

    public function getWebsiteMenus(Website $website)
    {
        return $website->menus()->where('is_active', true)->get();
    }

    public function getMenuByLocation(Website $website, string $location): ?Menu
    {
        return $website->menus()
            ->where('location', $location)
            ->where('is_active', true)
            ->first();
    }

    public function addMenuItem(Menu $menu, array $item): Menu
    {
        $items = $menu->items ?? [];
        $items[] = $item;
        $menu->update(['items' => $items]);
        return $menu;
    }

    public function removeMenuItem(Menu $menu, int $index): Menu
    {
        $items = $menu->items ?? [];
        if (isset($items[$index])) {
            unset($items[$index]);
            $menu->update(['items' => array_values($items)]);
        }
        return $menu;
    }

    public function updateMenuItems(Menu $menu, array $items): Menu
    {
        $menu->update(['items' => $items]);
        return $menu;
    }

    public function toggleMenu(Menu $menu): bool
    {
        $menu->is_active = !$menu->is_active;
        $menu->save();
        return true;
    }

    public function getMenuLocations(): array
    {
        return [
            'primary' => 'Primary Navigation',
            'secondary' => 'Secondary Navigation',
            'footer' => 'Footer Navigation',
            'social' => 'Social Links',
            'mobile' => 'Mobile Navigation',
        ];
    }
}