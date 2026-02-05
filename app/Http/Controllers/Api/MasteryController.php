<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\MasteryService;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class MasteryController extends ApiController
{
    public function __construct(protected MasteryService $masteryService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $safe = $request->validate([
            'search' => ['nullable', 'string'],
            'sort' => ['nullable', 'array'],
            'limit' => ['nullable', 'numeric'],
            'page' => ['nullable', 'numeric'],
        ]);

        $learnings = $this->masteryService->getPaginatedFiltered($safe);

        return $this->json($learnings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $safe = $request->validate(['name' => ['required', 'string']]);

        $mastery = $this->masteryService->createMastery($safe);

        return $this->json($mastery);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
