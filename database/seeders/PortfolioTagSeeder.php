<?php
// database/seeders/PortfolioTagSeeder.php

namespace Database\Seeders;

use App\Models\PortfolioTag;
use Illuminate\Database\Seeder;

class PortfolioTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            [
                'name' => 'React',
                'slug' => 'react',
                'description' => 'React.js projects and applications.',
                'icon' => 'fa-react',
                'color' => 'blue',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Vue.js',
                'slug' => 'vuejs',
                'description' => 'Vue.js projects and applications.',
                'icon' => 'fa-vuejs',
                'color' => 'green',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Laravel',
                'slug' => 'laravel',
                'description' => 'Laravel PHP framework projects.',
                'icon' => 'fa-laravel',
                'color' => 'red',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Node.js',
                'slug' => 'nodejs',
                'description' => 'Node.js applications and APIs.',
                'icon' => 'fa-node-js',
                'color' => 'green',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Python',
                'slug' => 'python',
                'description' => 'Python applications and data science.',
                'icon' => 'fa-python',
                'color' => 'blue',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Django',
                'slug' => 'django',
                'description' => 'Django web framework projects.',
                'icon' => 'fa-django',
                'color' => 'green',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Flutter',
                'slug' => 'flutter',
                'description' => 'Flutter mobile applications.',
                'icon' => 'fa-flutter',
                'color' => 'blue',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'iOS',
                'slug' => 'ios',
                'description' => 'iOS native applications.',
                'icon' => 'fa-apple',
                'color' => 'gray',
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Android',
                'slug' => 'android',
                'description' => 'Android native applications.',
                'icon' => 'fa-android',
                'color' => 'green',
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'AWS',
                'slug' => 'aws',
                'description' => 'Amazon Web Services cloud solutions.',
                'icon' => 'fa-aws',
                'color' => 'orange',
                'is_active' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'Docker',
                'slug' => 'docker',
                'description' => 'Docker containerization projects.',
                'icon' => 'fa-docker',
                'color' => 'blue',
                'is_active' => true,
                'sort_order' => 11,
            ],
            [
                'name' => 'Kubernetes',
                'slug' => 'kubernetes',
                'description' => 'Kubernetes orchestration projects.',
                'icon' => 'fa-kubernetes',
                'color' => 'blue',
                'is_active' => true,
                'sort_order' => 12,
            ],
        ];

        foreach ($tags as $tag) {
            PortfolioTag::create($tag);
        }
    }
}