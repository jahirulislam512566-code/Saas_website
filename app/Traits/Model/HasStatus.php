<?php

namespace App\Traits\Model;

use Illuminate\Support\Collection;

trait HasStatus
{
    /**
     * Get the status column.
     */
    protected function getStatusColumn(): string
    {
        return $this->statusColumn ?? 'status';
    }

    /**
     * Get the statuses.
     */
    public function getStatuses(): array
    {
        return $this->statuses ?? [];
    }

    /**
     * Check if model has a specific status.
     */
    public function hasStatus(string $status): bool
    {
        return $this->{$this->getStatusColumn()} === $status;
    }

    /**
     * Check if model is active.
     */
    public function isActive(): bool
    {
        return $this->hasStatus('active');
    }

    /**
     * Check if model is inactive.
     */
    public function isInactive(): bool
    {
        return $this->hasStatus('inactive');
    }

    /**
     * Set status.
     */
    public function setStatus(string $status): bool
    {
        if (!in_array($status, $this->getStatuses())) {
            return false;
        }

        return $this->update([$this->getStatusColumn() => $status]);
    }

    /**
     * Activate model.
     */
    public function activate(): bool
    {
        return $this->setStatus('active');
    }

    /**
     * Deactivate model.
     */
    public function deactivate(): bool
    {
        return $this->setStatus('inactive');
    }

    /**
     * Get status badge.
     */
    public function getStatusBadge(): string
    {
        $colors = [
            'active' => 'success',
            'inactive' => 'secondary',
            'pending' => 'warning',
            'suspended' => 'danger',
            'deactivated' => 'dark',
        ];

        $status = $this->{$this->getStatusColumn()};
        $color = $colors[$status] ?? 'secondary';

        return '<span class="badge badge-' . $color . '">' . ucfirst($status) . '</span>';
    }

    /**
     * Scope active models.
     */
    public function scopeActive($query)
    {
        return $query->where($this->getStatusColumn(), 'active');
    }

    /**
     * Scope inactive models.
     */
    public function scopeInactive($query)
    {
        return $query->where($this->getStatusColumn(), 'inactive');
    }

    /**
     * Scope pending models.
     */
    public function scopePending($query)
    {
        return $query->where($this->getStatusColumn(), 'pending');
    }
}