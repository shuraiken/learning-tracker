<?php

namespace App\Http\Controllers;

use App\Models\LearningSession;
use App\Services\LearningSessionService;

class ActivateLearningSessionController extends Controller
{
    public function store(int $id, LearningSessionService $learningSessionService)
    {
        try {
            $learningSession = LearningSession::findOrFail($id);

            $learningSessionService->activateLearningSession($learningSession);

            return $this->json($learningSession->fresh());
        } catch (\Exception $e) {
            return $this->jsonException($e, "Failed to activate session");
        }
    }
}
