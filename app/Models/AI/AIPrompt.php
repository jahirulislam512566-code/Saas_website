<?php

// AI/AIPrompt.php
namespace App\Models\AI;

use Illuminate\Database\Eloquent\Model;

class AIPrompt extends Model
{
    protected $fillable = [
        'title', 'description', 'category_id', 'tool', 'prompt_text', 
        'variables', 'is_public', 'is_favorite', 'usage_count'
    ];
    
    protected $casts = [
        'variables' => 'array',
        'is_public' => 'boolean',
        'is_favorite' => 'boolean'
    ];
    
    public function category()
    {
        return $this->belongsTo(AIPromptCategory::class);
    }
}

// AI/AIPromptCategory.php
namespace App\Models\AI;

use Illuminate\Database\Eloquent\Model;

class AIPromptCategory extends Model
{
    protected $fillable = ['name', 'description', 'icon', 'color'];
    
    public function prompts()
    {
        return $this->hasMany(AIPrompt::class);
    }
}

// AI/AIUsage.php
namespace App\Models\AI;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AIUsage extends Model
{
    protected $fillable = [
        'user_id', 'tool', 'prompt', 'response', 'tokens', 'cost', 
        'duration', 'status', 'metadata'
    ];
    
    protected $casts = [
        'metadata' => 'array'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}