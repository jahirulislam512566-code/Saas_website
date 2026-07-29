<?php

namespace App\Rules\Content;

use App\Rules\BaseRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UniqueSlugRule extends BaseRule
{
    protected string $table;
    protected string $column;
    protected ?int $ignoreId;
    protected ?string $ignoreColumn;
    protected ?string $titleColumn;

    public function __construct(
        string $table,
        string $column = 'slug',
        ?int $ignoreId = null,
        ?string $ignoreColumn = 'id',
        ?string $titleColumn = 'title'
    ) {
        $this->table = $table;
        $this->column = $column;
        $this->ignoreId = $ignoreId;
        $this->ignoreColumn = $ignoreColumn;
        $this->titleColumn = $titleColumn;
    }

    public function passes($attribute, $value): bool
    {
        // If value is not a slug, generate one
        $slug = $this->isSlug($value) ? $value : Str::slug($value);

        $query = DB::table($this->table)
            ->where($this->column, $slug);

        if ($this->ignoreId) {
            $query->where($this->ignoreColumn, '!=', $this->ignoreId);
        }

        return !$query->exists();
    }

    protected function isSlug(string $value): bool
    {
        return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value) === 1;
    }

    public function message(): string
    {
        return 'The slug must be unique.';
    }

    /**
     * Generate a unique slug
     */
    public function generate(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (!empty($this->getExistingSlug($slug))) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }

    protected function getExistingSlug(string $slug): ?string
    {
        $query = DB::table($this->table)
            ->where($this->column, $slug);

        if ($this->ignoreId) {
            $query->where($this->ignoreColumn, '!=', $this->ignoreId);
        }

        $result = $query->first([$this->column]);

        return $result ? $result->{$this->column} : null;
    }
}