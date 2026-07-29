<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->paginate($perPage, $columns);
    }

    public function find(int $id, array $columns = ['*']): ?Model
    {
        return $this->model->find($id, $columns);
    }

    public function findOrFail(int $id, array $columns = ['*']): Model
    {
        return $this->model->findOrFail($id, $columns);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): Model
    {
        $model->update($data);
        return $model->fresh();
    }

    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    public function deleteById(int $id): bool
    {
        return $this->model->destroy($id) > 0;
    }

    public function firstOrCreate(array $attributes, array $values = []): Model
    {
        return $this->model->firstOrCreate($attributes, $values);
    }

    public function updateOrCreate(array $attributes, array $values = []): Model
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    public function count(): int
    {
        return $this->model->count();
    }

    public function where(array $conditions, array $columns = ['*']): Collection
    {
        $query = $this->model->newQuery();
        
        foreach ($conditions as $key => $value) {
            $query->where($key, $value);
        }
        
        return $query->get($columns);
    }

    public function whereFirst(array $conditions, array $columns = ['*']): ?Model
    {
        $query = $this->model->newQuery();
        
        foreach ($conditions as $key => $value) {
            $query->where($key, $value);
        }
        
        return $query->first($columns);
    }

    public function wherePaginate(array $conditions, int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        
        foreach ($conditions as $key => $value) {
            $query->where($key, $value);
        }
        
        return $query->paginate($perPage, $columns);
    }

    public function with(array $relations): self
    {
        $this->model = $this->model->with($relations);
        return $this;
    }

    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->model = $this->model->orderBy($column, $direction);
        return $this;
    }

    public function pluck(string $column, ?string $key = null): Collection
    {
        return $this->model->pluck($column, $key);
    }

    public function exists(array $conditions): bool
    {
        $query = $this->model->newQuery();
        
        foreach ($conditions as $key => $value) {
            $query->where($key, $value);
        }
        
        return $query->exists();
    }

    public function beginTransaction(): void
    {
        \DB::beginTransaction();
    }

    public function commitTransaction(): void
    {
        \DB::commit();
    }

    public function rollbackTransaction(): void
    {
        \DB::rollBack();
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function setModel(Model $model): self
    {
        $this->model = $model;
        return $this;
    }
}