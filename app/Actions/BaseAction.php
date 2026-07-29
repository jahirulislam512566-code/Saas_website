<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\ActionException;

abstract class BaseAction
{
    protected array $validationRules = [];
    protected array $data = [];
    protected array $errors = [];
    protected bool $shouldCommit = true;
    
    /**
     * Execute the action
     */
    abstract public function execute(array $data);
    
    /**
     * Validate data before execution
     */
    protected function validate(array $data): void
    {
        $validator = validator($data, $this->validationRules);
        
        if ($validator->fails()) {
            $this->errors = $validator->errors()->toArray();
            throw new ActionException('Validation failed', $this->errors);
        }
    }
    
    /**
     * Begin database transaction
     */
    protected function beginTransaction(): void
    {
        if ($this->shouldCommit) {
            DB::beginTransaction();
        }
    }
    
    /**
     * Commit database transaction
     */
    protected function commit(): void
    {
        if ($this->shouldCommit) {
            DB::commit();
        }
    }
    
    /**
     * Rollback database transaction
     */
    protected function rollback(): void
    {
        if ($this->shouldCommit) {
            DB::rollBack();
        }
    }
    
    /**
     * Log error
     */
    protected function logError(string $message, array $context = []): void
    {
        Log::error($message, array_merge($context, [
            'action' => static::class,
            'data' => $this->data,
        ]));
    }
    
    /**
     * Dispatch event
     */
    protected function dispatchEvent(string $event, array $payload = []): void
    {
        event(new $event($payload));
    }
}