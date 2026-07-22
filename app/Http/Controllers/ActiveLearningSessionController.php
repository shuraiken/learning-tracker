<?php

namespace App\Http\Controllers;

use App\Services\LearningSessionService;
use Illuminate\Http\Request;

class ActiveLearningSessionController extends Controller
{
    public function __construct(protected LearningSessionService $learningSessionService)
    {}

    public function index()
    {
        try {
            $learningSession = $this->learningSessionService->getCurrentSession();
            return $this->json($learningSession);
        } catch (\Exception $e) {
            return $this->jsonException($e);
        }
    }
}
