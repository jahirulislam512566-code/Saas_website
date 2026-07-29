<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use App\Traits\Model\HasAuditTrail;
use App\Traits\Model\HasSlug;
use App\Traits\Model\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory, SoftDeletes, HasUuid, HasSlug, HasAuditTrail;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'project_code',
        'name',
        'slug',
        'description',
        'project_type',
        'customer_id',
        'start_date',
        'end_date',
        'budget',
        'status',
        'priority',
        'assigned_team',
        'assigned_to',
        'technologies',
        'requirements',
        'completion_notes',
        'completed_at',
        'archived_at',
        'archive_reason',
        'archive_notes',
        'completion_report',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'completed_at' => 'datetime',
        'archived_at' => 'datetime',
        'budget' => 'decimal:2',
        'assigned_team' => 'array',
        'technologies' => 'array',
        'completion_report' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
        'deleted_by',
    ];

    /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array<string>
     */
    protected $appends = [
        'status_label',
        'status_color',
        'status_icon',
        'is_active',
        'is_finished',
        'progress_percentage',
        'formatted_budget',
        'full_address',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array<string>
     */
    protected $with = [];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 15;

    /**
     * The source column for slug generation.
     */
    protected string $slugSource = 'name';

    /**
     * The column name for status.
     */
    protected string $statusColumn = 'status';

    /**
     * Get the customer that owns the project.
     */


     public function getRouteKeyName(): string
    {
        return 'slug';
    }
    
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withDefault([
            'name' => 'Unknown Customer',
            'email' => 'no-email@example.com',
        ]);
    }

    /**
     * Get the user who created the project.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the project.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted the project.
     */
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Get the user assigned to the project.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the team members assigned to the project.
     */
    public function teamMembers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_team_members')
            ->withPivot(['role', 'assigned_at'])
            ->withTimestamps();
    }

    /**
     * Get the milestones for the project.
     */
    public function milestones(): HasMany
    {
        return $this->hasMany(ProjectMilestone::class)->orderBy('order');
    }

    /**
     * Get the tasks for the project.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the invoices for the project.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the time logs for the project.
     */
    public function timeLogs(): HasMany
    {
        return $this->hasMany(TimeLog::class);
    }

    /**
     * Get the comments for the project.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(ProjectComment::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the files for the project.
     */
    public function files(): HasMany
    {
        return $this->hasMany(ProjectFile::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the activities for the project.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(ProjectActivity::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the notifications for the project.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(ProjectNotification::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the project's associated media.
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    /**
     * Scope a query to only include active projects.
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'archived')
                     ->where('status', '!=', 'cancelled')
                     ->where('status', '!=', 'completed');
    }

    /**
     * Scope a query to only include in-progress projects.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope a query to only include planning projects.
     */
    public function scopePlanning($query)
    {
        return $query->where('status', 'planning');
    }

    /**
     * Scope a query to only include completed projects.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include cancelled projects.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope a query to only include archived projects.
     */
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    /**
     * Scope a query to only include projects on hold.
     */
    public function scopeOnHold($query)
    {
        return $query->where('status', 'on_hold');
    }

    /**
     * Scope a query to filter by project type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('project_type', $type);
    }

    /**
     * Scope a query to filter by priority.
     */
    public function scopeWithPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to filter by customer.
     */
    public function scopeForCustomer($query, int $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope a query to filter by assigned user.
     */
    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where('assigned_to', $userId)
                     ->orWhereHas('teamMembers', function ($q) use ($userId) {
                         $q->where('user_id', $userId);
                     });
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope a query to search for projects.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('project_code', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%")
              ->orWhereHas('customer', function ($q2) use ($search) {
                  $q2->where('name', 'LIKE', "%{$search}%")
                     ->orWhere('email', 'LIKE', "%{$search}%");
              });
        });
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status ? ProjectStatus::fromValue($this->status)->label() : ucfirst($this->status);
    }

    /**
     * Get the status color.
     */
    public function getStatusColorAttribute(): string
    {
        return $this->status ? ProjectStatus::fromValue($this->status)->color() : '#6c757d';
    }

    /**
     * Get the status icon.
     */
    public function getStatusIconAttribute(): string
    {
        return $this->status ? ProjectStatus::fromValue($this->status)->icon() : 'fa-circle';
    }

    /**
     * Get the status description.
     */
    public function getStatusDescriptionAttribute(): string
    {
        return $this->status ? ProjectStatus::fromValue($this->status)->description() : '';
    }

    /**
     * Check if project is active.
     */
    public function getIsActiveAttribute(): bool
    {
        return in_array($this->status, ['planning', 'in_progress', 'review', 'testing']);
    }

    /**
     * Check if project is finished.
     */
    public function getIsFinishedAttribute(): bool
    {
        return in_array($this->status, ['completed', 'cancelled', 'archived']);
    }

    /**
     * Get the progress percentage.
     */
    public function getProgressPercentageAttribute(): int
    {
        if ($this->status === 'completed') {
            return 100;
        }

        if ($this->status === 'cancelled') {
            return 0;
        }

        if ($this->status === 'archived') {
            return 100;
        }

        // Calculate based on milestones
        $totalMilestones = $this->milestones()->count();
        if ($totalMilestones === 0) {
            return $this->status === 'planning' ? 10 : 50;
        }

        $completedMilestones = $this->milestones()->where('status', 'completed')->count();
        return (int) round(($completedMilestones / $totalMilestones) * 100);
    }

    /**
     * Get the formatted budget.
     */
    public function getFormattedBudgetAttribute(): string
    {
        return $this->budget ? money($this->budget) : 'Not set';
    }

    /**
     * Get the full address (if applicable).
     */
    public function getFullAddressAttribute(): ?string
    {
        return $this->address ? 
            "{$this->address}, {$this->city}, {$this->state} {$this->postal_code}, {$this->country}" : 
            null;
    }

    /**
     * Get the days remaining or days overdue.
     */
    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->end_date) {
            return null;
        }

        if ($this->status === 'completed') {
            return 0;
        }

        $now = now();
        $endDate = $this->end_date;

        if ($endDate->isPast()) {
            return $endDate->diffInDays($now) * -1; // Negative for overdue
        }

        return $now->diffInDays($endDate);
    }

    /**
     * Get the days since project started.
     */
    public function getDaysRunningAttribute(): int
    {
        $start = $this->start_date ?? $this->created_at;
        return $start->diffInDays(now());
    }

    /**
     * Get the total tasks count.
     */
    public function getTasksCountAttribute(): int
    {
        return $this->tasks()->count();
    }

    /**
     * Get the completed tasks count.
     */
    public function getCompletedTasksCountAttribute(): int
    {
        return $this->tasks()->where('status', 'completed')->count();
    }

    /**
     * Get the pending tasks count.
     */
    public function getPendingTasksCountAttribute(): int
    {
        return $this->tasks()->where('status', 'pending')->count();
    }

    /**
     * Get the in-progress tasks count.
     */
    public function getInProgressTasksCountAttribute(): int
    {
        return $this->tasks()->where('status', 'in_progress')->count();
    }

    /**
     * Get the total milestones count.
     */
    public function getMilestonesCountAttribute(): int
    {
        return $this->milestones()->count();
    }

    /**
     * Get the completed milestones count.
     */
    public function getCompletedMilestonesCountAttribute(): int
    {
        return $this->milestones()->where('status', 'completed')->count();
    }

    /**
     * Get the pending milestones count.
     */
    public function getPendingMilestonesCountAttribute(): int
    {
        return $this->milestones()->where('status', 'pending')->count();
    }

    /**
     * Get the total comments count.
     */
    public function getCommentsCountAttribute(): int
    {
        return $this->comments()->count();
    }

    /**
     * Get the total files count.
     */
    public function getFilesCountAttribute(): int
    {
        return $this->files()->count();
    }

    /**
     * Get the total time logged in hours.
     */
    public function getTotalTimeLoggedAttribute(): float
    {
        return $this->timeLogs()->sum('hours') ?? 0;
    }

    /**
     * Get the total cost based on time logged.
     */
    public function getTotalTimeCostAttribute(): float
    {
        $totalHours = $this->getTotalTimeLoggedAttribute();
        $ratePerHour = $this->hourly_rate ?? 0;
        return $totalHours * $ratePerHour;
    }

    /**
     * Get the project's featured image.
     */
    public function getFeaturedImageAttribute(): ?string
    {
        $media = $this->media()->where('collection', 'featured')->first();
        return $media ? $media->url : null;
    }

    /**
     * Get the project's gallery images.
     */
    public function getGalleryImagesAttribute(): array
    {
        return $this->media()->where('collection', 'gallery')->get()->pluck('url')->toArray();
    }

    /**
     * Check if project can be edited by a user.
     */
    public function canBeEditedBy(User $user): bool
    {
        return $user->isSuperAdmin() || 
               $user->isAdmin() || 
               $this->assigned_to === $user->id ||
               $this->teamMembers()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if project can be deleted by a user.
     */
    public function canBeDeletedBy(User $user): bool
    {
        return $user->isSuperAdmin() || 
               ($user->isAdmin() && $this->status !== 'in_progress');
    }

    /**
     * Mark project as completed.
     */
    public function markAsCompleted(array $data = []): bool
    {
        return $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completion_notes' => $data['notes'] ?? null,
            'completion_report' => $data['report'] ?? null,
            'updated_by' => $data['updated_by'] ?? auth()->id(),
        ]);
    }

    /**
     * Mark project as archived.
     */
    public function markAsArchived(array $data = []): bool
    {
        return $this->update([
            'status' => 'archived',
            'archived_at' => now(),
            'archive_reason' => $data['reason'] ?? 'No reason provided',
            'archive_notes' => $data['notes'] ?? null,
            'updated_by' => $data['updated_by'] ?? auth()->id(),
        ]);
    }

    /**
     * Mark project as cancelled.
     */
    public function markAsCancelled(array $data = []): bool
    {
        return $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $data['reason'] ?? 'No reason provided',
            'cancellation_notes' => $data['notes'] ?? null,
            'updated_by' => $data['updated_by'] ?? auth()->id(),
        ]);
    }

    /**
     * Mark project as on hold.
     */
    public function markAsOnHold(array $data = []): bool
    {
        return $this->update([
            'status' => 'on_hold',
            'on_hold_at' => now(),
            'on_hold_reason' => $data['reason'] ?? 'No reason provided',
            'on_hold_notes' => $data['notes'] ?? null,
            'updated_by' => $data['updated_by'] ?? auth()->id(),
        ]);
    }

    /**
     * Resume project from on hold.
     */
    public function resume(array $data = []): bool
    {
        return $this->update([
            'status' => 'in_progress',
            'on_hold_at' => null,
            'on_hold_reason' => null,
            'on_hold_notes' => null,
            'updated_by' => $data['updated_by'] ?? auth()->id(),
        ]);
    }

    /**
     * Get the next status in the workflow.
     */
    public function getNextStatus(): ?string
    {
        $flow = [
            'planning' => 'in_progress',
            'in_progress' => 'review',
            'review' => 'completed',
            'on_hold' => 'in_progress',
        ];

        return $flow[$this->status] ?? null;
    }

    /**
     * Get allowed status transitions.
     */
    public function getAllowedTransitions(): array
    {
        return [
            'planning' => ['in_progress', 'cancelled'],
            'in_progress' => ['review', 'on_hold', 'cancelled'],
            'review' => ['in_progress', 'completed', 'cancelled'],
            'on_hold' => ['in_progress', 'cancelled'],
            'completed' => ['archived'],
            'archived' => [],
            'cancelled' => [],
        ][$this->status] ?? [];
    }

    /**
     * Get the project type label.
     */
    public function getProjectTypeLabelAttribute(): string
    {
        $types = [
            'website' => 'Website',
            'logo' => 'Logo Design',
            'seo' => 'SEO',
            'mobile_app' => 'Mobile App',
            'consulting' => 'Consulting',
            'graphic_design' => 'Graphic Design',
            'content_writing' => 'Content Writing',
            'social_media' => 'Social Media',
            'digital_marketing' => 'Digital Marketing',
            'maintenance' => 'Maintenance',
            'hosting' => 'Hosting',
        ];

        return $types[$this->project_type] ?? ucfirst(str_replace('_', ' ', $this->project_type));
    }

    /**
     * Get the priority label.
     */
    public function getPriorityLabelAttribute(): string
    {
        $priorities = [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent',
        ];

        return $priorities[$this->priority] ?? ucfirst($this->priority);
    }

    /**
     * Get the priority color.
     */
    public function getPriorityColorAttribute(): string
    {
        $colors = [
            'low' => '#28a745',
            'medium' => '#17a2b8',
            'high' => '#ffc107',
            'urgent' => '#dc3545',
        ];

        return $colors[$this->priority] ?? '#6c757d';
    }

    /**
     * Get the priority icon.
     */
    public function getPriorityIconAttribute(): string
    {
        $icons = [
            'low' => 'fa-arrow-down',
            'medium' => 'fa-arrow-right',
            'high' => 'fa-arrow-up',
            'urgent' => 'fa-exclamation-triangle',
        ];

        return $icons[$this->priority] ?? 'fa-circle';
    }

    /**
     * Check if project has passed its end date.
     */
    public function getIsOverdueAttribute(): bool
    {
        if (!$this->end_date || $this->status === 'completed' || $this->status === 'cancelled') {
            return false;
        }

        return $this->end_date->isPast();
    }

    /**
     * Get the URL for the project.
     */
    public function getUrlAttribute(): string
    {
        return route('admin.projects.show', $this->id);
    }

    /**
     * Get the API URL for the project.
     */
    public function getApiUrlAttribute(): string
    {
        return route('api.projects.show', $this->id);
    }
}