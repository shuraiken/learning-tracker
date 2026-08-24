<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivateLearningSessionController extends Controller
{
    public function store(int $id)
    {
        try {
            $session = \App\Models\LearningSession::findOrFail($id);
            $session->update(['status' => 'active']);
            return $this->json(['message' => 'Session activated successfully']);
        } catch (\Exception $e) {
            return $this->jsonException($e, "Failed to activate session");
        }
    }
}
