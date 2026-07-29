<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class MenuHelper
{
    /**
     * Check if menu is active
     */
    public static function isActive($route, $exact = false): bool
    {
        if (is_array($route)) {
            foreach ($route as $r) {
                if (self::isActive($r, $exact)) {
                    return true;
                }
            }
            return false;
        }

        $currentRoute = Route::currentRouteName();

        if ($exact) {
            return $currentRoute === $route;
        }

        // Check for route pattern matching
        if (strpos($route, '*') !== false) {
            $pattern = str_replace('*', '.*', $route);
            return preg_match('/^' . $pattern . '$/', $currentRoute) === 1;
        }

        return $currentRoute === $route || Str::startsWith($currentRoute, $route . '.');
    }

    /**
     * Get active class
     */
    public static function activeClass($route, $class = 'active'): string
    {
        return self::isActive($route) ? $class : '';
    }

    /**
     * Get active menu item with parent
     */
    public static function isParentActive($parent, $children): bool
    {
        if (self::isActive($parent)) {
            return true;
        }

        foreach ($children as $child) {
            if (self::isActive($child)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate breadcrumbs
     */
    public static function breadcrumbs(): array
    {
        $segments = request()->segments();
        $breadcrumbs = [];
        $url = '';

        foreach ($segments as $segment) {
            $url .= '/' . $segment;
            $breadcrumbs[] = [
                'label' => ucwords(str_replace(['-', '_'], ' ', $segment)),
                'url' => $url,
                'active' => request()->fullUrl() === url($url),
            ];
        }

        return $breadcrumbs;
    }

    /**
     * Get current menu item
     */
    public static function currentMenuItem($menu): ?array
    {
        foreach ($menu as $item) {
            if (self::isActive($item['route'] ?? null)) {
                return $item;
            }

            if (isset($item['children'])) {
                $child = self::currentMenuItem($item['children']);
                if ($child) {
                    return $child;
                }
            }
        }

        return null;
    }

    /**
     * Build navigation menu
     */
    public static function buildMenu($items, $view = 'partials.menu'): string
    {
        $html = '<ul class="nav navbar-nav">';

        foreach ($items as $item) {
            $active = self::isActive($item['route'] ?? null);
            $hasChildren = isset($item['children']) && !empty($item['children']);

            $html .= '<li class="nav-item' . ($active ? ' active' : '') . ($hasChildren ? ' dropdown' : '') . '">';

            if ($hasChildren) {
                $html .= '<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                            ' . ($item['icon'] ?? '') . ' ' . ($item['label'] ?? '') . '
                            <span class="caret"></span>
                         </a>';
                $html .= '<ul class="dropdown-menu">';
                foreach ($item['children'] as $child) {
                    $childActive = self::isActive($child['route'] ?? null);
                    $html .= '<li class="' . ($childActive ? 'active' : '') . '">
                                <a class="dropdown-item" href="' . url($child['route'] ?? '#') . '">
                                    ' . ($child['icon'] ?? '') . ' ' . ($child['label'] ?? '') . '
                                </a>
                             </li>';
                }
                $html .= '</ul>';
            } else {
                $html .= '<a class="nav-link' . ($active ? ' active' : '') . '" href="' . url($item['route'] ?? '#') . '">
                            ' . ($item['icon'] ?? '') . ' ' . ($item['label'] ?? '') . '
                         </a>';
            }

            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }
}