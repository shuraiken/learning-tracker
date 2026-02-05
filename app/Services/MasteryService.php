<?php

namespace App\Services;

use App\Models\Mastery;
use Illuminate\Pagination\LengthAwarePaginator;

class MasteryService
{
    public function __construct()
    {
    }

    public function getFilteredQuery(array $filters, array $relations = [])
    {
        return Mastery::query()
            ->with($relations)
            ->when($filters['search'], function ($query) use ($filters) {
                $query->where('name', 'like', '%' . $filters['search'] . '%');
            })
            ->when(isset($filters['column']) && isset($filters['direction']), function ($query) use ($filters) {
                $query->orderBy($filters['column'], $filters['direction']);
            });
    }

    public function getPaginatedFiltered(array $filters, array $relations = [], callable $callback = null): LengthAwarePaginator
    {
        $filtered = $this->getFilteredQuery($filters, $relations);

        if ($callback) {
            $callback($filtered);
        }

        return $filtered->paginate($filters['limit'], ['*'], 'page', $filters['page'] ?? null);
    }

    public function getMasteryById($id, $relations = [])
    {
        return Mastery::with($relations)->findOrFail($id);
    }

    public function createMastery(array $data)
    {
        return Mastery::create([
            'user_id' => auth()->user()->id,
            'name' => $data['name'],
        ]);
    }

    public function updateMastery(array $data, $id)
    {
        return Mastery::where('id', $id)->update([
            'name' => $data['name'],
        ]);
    }

    public function deleteMastery($id)
    {
        return Mastery::where('id', $id)->delete();
    }
}
