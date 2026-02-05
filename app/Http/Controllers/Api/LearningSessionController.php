<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\LearningSession;
use App\Http\Controllers\Controller;
use App\Services\LearningSessionService;
use App\Exceptions\ActiveSessionAlreadyExistsException;

class LearningSessionController extends Controller
{
    public function __construct(protected LearningSessionService $learningSessionService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function getCurrentSession(Request $request)
    {
        $learningSession = $this->learningSessionService->getCurrentSession();

        return $this->json($learningSession);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $safe = $request->validate([
            'learningId' => ['required', 'exists:learnings,id'],
            'name' => ['nullable', 'string'],
            'note' => ['nullable', 'string'],
        ]);

        try {
            $learningSession = $this->learningSessionService->createLearningSession($safe);

            return $this->json($learningSession);
        } catch (ActiveSessionAlreadyExistsException $e) {
            return $this->jsonException($e);
        } catch (\Exception $e) {
            return $this->jsonException($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LearningSession $learningSession)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LearningSession $learningSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LearningSession $learningSession)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LearningSession $learningSession)
    {
        //
    }
}
