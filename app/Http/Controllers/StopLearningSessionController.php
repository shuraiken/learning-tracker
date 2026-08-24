<?php

namespace App\Http\Controllers;

use App\Models\LearningSession;
use App\Services\LearningSessionService;
use Illuminate\Http\Request;

class StopLearningSessionController extends Controller
{
    public function store(int $id, LearningSessionService $learningSessionService)
    {
        try {
            $learningSession = LearningSession::findOrFail($id);

            $learningSessionService->stopLearningSession($learningSession);

            return $this->json($learningSession->fresh());
        } catch (\Exception $e) {
            return $this->jsonException($e);
        }
    }
}
