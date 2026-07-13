<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class AdminHelper
{
    public static function getAdminModules()
    {
        $modules = [];
        $adminPath = app_path('Http/Controllers/Admin');
        
        if (File::exists($adminPath)) {
            $files = File::files($adminPath);
            
            foreach ($files as $file) {
                $name = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                if ($name !== 'Controller' && !str_ends_with($name, 'Controller')) {
                    continue;
                }
                
                $moduleName = str_replace('Controller', '', $name);
                $modules[] = [
                    'name' => $moduleName,
                    'lowercase' => strtolower($moduleName),
                    'route' => "admin.{$moduleName}.index",
                    'icon' => self::getIconForModule($moduleName),
                ];
            }
        }
        
        return $modules;
    }

    public static function getIconForModule($module)
    {
        $icons = [
            'User' => 'fa-users',
            'Role' => 'fa-user-tag',
            'Permission' => 'fa-lock',
            'Plan' => 'fa-cubes',
            'Subscription' => 'fa-sync',
            'Payment' => 'fa-credit-card',
            'Invoice' => 'fa-file-invoice',
            'Ticket' => 'fa-ticket-alt',
            'Support' => 'fa-headset',
            'Media' => 'fa-images',
            'Setting' => 'fa-cog',
            'Team' => 'fa-users-cog',
            'Dashboard' => 'fa-tachometer-alt',
            'Report' => 'fa-chart-bar',
        ];
        
        return $icons[$module] ?? 'fa-cube';
    }
}