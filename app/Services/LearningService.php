<?php

namespace App\Services;

use App\Models\Learning;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class LearningService
{
    public function __construct()
    {
    }

    public function getFilteredQuery(array $filters, array $relations = []): Builder
    {
        return Learning::query()
            ->with($relations)
            ->when(isset($filters['groupId']), function ($query) use ($filters) {
                $query->where('group_id', $filters['group_id']);
            })
            ->when(isset($filters['masteryId']), function ($query) use ($filters) {
                $query->where('mastery_id', $filters['mastery_id']);
            })
            // ->when(isset($filters['tags']), function ($query) use ($filters) {
            //     $tags = $filters['tags'];
            //     if (gettype($tags) === 'string') {
            //         $tags = explode(',', $filters['tags']);
            //     }

            //     $query->whereHas('tags', function ($query) use ($tags) {
            //         $query->whereIn('tag_id', $tags);
            //     });
            // })
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

    public function getLearningById($id, $relations = [])
    {
        return Learning::with($relations)->findOrFail($id);
    }

    public function createLearning(array $data)
    {
        return Learning::create([
            'user_id' => auth()->user()->id,
            'group_id' => $data['groupId'] ?? null,
            'mastery_id' => $data['masteryId'] ?? null,
            'name' => $data['name'],
        ]);
    }

    public function updateLearning(array $data, int $id)
    {
        return Learning::findOrFail($id)->update([
            'mastery_id' => $data['mastery_id'] ?? null,
            'name' => $data['name'],
        ]);
    }

    public function deleteLearning($id)
    {
        return Learning::where('id', $id)->delete();
    }
}
