<?php

namespace App\Rules\Customer;

use App\Rules\BaseRule;
use Illuminate\Support\Facades\DB;

class UniquePhoneRule extends BaseRule
{
    protected string $table;
    protected string $column;
    protected ?int $ignoreId;
    protected ?string $ignoreColumn;

    public function __construct(
        string $table = 'customers',
        string $column = 'phone',
        ?int $ignoreId = null,
        ?string $ignoreColumn = 'id'
    ) {
        $this->table = $table;
        $this->column = $column;
        $this->ignoreId = $ignoreId;
        $this->ignoreColumn = $ignoreColumn;
    }

    public function passes($attribute, $value): bool
    {
        if (empty($value)) {
            return true;
        }

        // Normalize phone number
        $phone = preg_replace('/[^0-9+]/', '', $value);

        $query = DB::table($this->table)
            ->where($this->column, $phone)
            ->orWhere($this->column, $value);

        if ($this->ignoreId) {
            $query->where($this->ignoreColumn, '!=', $this->ignoreId);
        }

        return !$query->exists();
    }

    public function message(): string
    {
        return 'The phone number is already in use.';
    }
}