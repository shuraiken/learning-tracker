<?php

namespace App\Http\Controllers;

use App\Services\LearningSessionService;
use App\Services\LearningSessionLogService;
use App\Exceptions\ActiveSessionAlreadyExistsException;
use Illuminate\Http\Request;

class ActivateLearningSessionAndLogController extends Controller
{
    public function __construct(protected LearningSessionService $learningSessionService, protected LearningSessionLogService $learningSessionLogService)
    {}

    public function store(Request $request)
    {
        $safe = $request->validate([
            'learning_id' => ['required', 'exists:learnings,id'],
            'name' => ['nullable', 'string'],
            'note' => ['nullable', 'string'],
        ]);

        try {
            [$learningSession, $learningSessionLog] = $this->learningSessionService->createLearningSessionAndRunSessionLog($safe);

            return $this->json(['learningSession' => $learningSession, 'learningSessionLog' => $learningSessionLog]);
        } catch (ActiveSessionAlreadyExistsException $e) {
            return $this->jsonException($e);
        } catch (\Exception $e) {
            return $this->jsonException($e);
        }
    }
}
