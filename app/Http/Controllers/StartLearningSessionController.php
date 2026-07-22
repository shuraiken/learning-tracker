<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LearningSession;
use App\Services\LearningSessionService;

class StartLearningSessionController extends Controller
{
    public function store(int $id, LearningSessionService $learningSessionService)
    {
        try {
            $learningSession = $learningSessionService->startLearningSession($id);

            return $this->json($learningSession->fresh());
        } catch (\Exception $e) {
            return $this->jsonException($e);
        }
    }
}
