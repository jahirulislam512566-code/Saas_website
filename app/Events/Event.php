<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class Event
{
    use Dispatchable, SerializesModels;

    /**
     * The timestamp when the event occurred
     */
    public $occurred_at;

    /**
     * The IP address where the event originated
     */
    public $ip_address;

    /**
     * The user agent where the event originated
     */
    public $user_agent;

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        $this->occurred_at = now();
        $this->ip_address = request()->ip();
        $this->user_agent = request()->userAgent();
    }

    /**
     * Get the event name
     */
    abstract public function eventName(): string;

    /**
     * Get the event payload
     */
    abstract public function payload(): array;
}