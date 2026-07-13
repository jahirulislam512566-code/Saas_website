<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Website extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'logo',
        'favicon',
        'timezone',
        'language',
        'is_active',
        'is_public',
        'settings',
        'published_at',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function forms()
    {
        return $this->hasMany(Form::class);
    }

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    public function themes()
    {
        return $this->belongsToMany(Theme::class, 'website_theme')
            ->withPivot('settings')
            ->withTimestamps();
    }

    public function settings()
    {
        return $this->hasMany(WebsiteSetting::class);
    }

    public function analytics()
    {
        return $this->hasMany(Analytic::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function newsletters()
    {
        return $this->hasMany(Newsletter::class);
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class);
    }

    // Helper methods
    public function getPrimaryDomain()
    {
        return $this->domains()->where('is_primary', true)->first();
    }

    public function getActiveTheme()
    {
        return $this->themes()->wherePivot('is_active', true)->first();
    }

    public function getHomePage()
    {
        return $this->pages()->where('is_homepage', true)->first();
    }
}