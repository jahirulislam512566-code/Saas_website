<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register model observers.
     */
    public function boot(): void
    {
        // Register all observers
        $this->registerUserObservers();
        $this->registerCustomerObservers();
        $this->registerOrderObservers();
        $this->registerProjectObservers();
        $this->registerInvoiceObservers();
        $this->registerTicketObservers();
        $this->registerPaymentObservers();
        $this->registerBlogObservers();
        $this->registerPortfolioObservers();
        $this->registerMediaObservers();
    }

    protected function registerUserObservers(): void
    {
        \App\Models\User::observe(\App\Observers\UserObserver::class);
    }

    protected function registerCustomerObservers(): void
    {
        \App\Models\Customer::observe(\App\Observers\CustomerObserver::class);
    }

    protected function registerOrderObservers(): void
    {
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);
    }

    protected function registerProjectObservers(): void
    {
        \App\Models\Project::observe(\App\Observers\ProjectObserver::class);
    }

    protected function registerInvoiceObservers(): void
    {
        \App\Models\Invoice::observe(\App\Observers\InvoiceObserver::class);
    }

    protected function registerTicketObservers(): void
    {
        \App\Models\Ticket::observe(\App\Observers\TicketObserver::class);
    }

    protected function registerPaymentObservers(): void
    {
        \App\Models\Payment::observe(\App\Observers\PaymentObserver::class);
    }

    protected function registerBlogObservers(): void
    {
        \App\Models\BlogPost::observe(\App\Observers\BlogPostObserver::class);
    }

    protected function registerPortfolioObservers(): void
    {
        \App\Models\Portfolio::observe(\App\Observers\PortfolioObserver::class);
    }

    protected function registerMediaObservers(): void
    {
        \App\Models\Media::observe(\App\Observers\MediaObserver::class);
    }
}