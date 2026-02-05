<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Services\LearningService;
use App\Http\Controllers\Controller;

class LearningController extends ApiController
{
    public function __construct(protected LearningService $learningService)
    {
    }

    /**
     * @OA\Get(
     *     path="/learnings",
     *     summary="Get a list of learnings",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="search",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="groupId",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     oneOf={
     *                     	   @OA\Schema(type="string"),
     *                     	   @OA\Schema(type="integer"),
     *                     }
     *                 ),
     *                 example={"id": "a3fb6", "name": "Jessica Smith", "phone": 12345678}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/Result"),
     *                 @OA\Schema(type="boolean")
     *             },
     *             @OA\Examples(example="result", value={"success": true}, summary="An result object."),
     *             @OA\Examples(example="bool", value=false, summary="A boolean value."),
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $safe = $request->validate([
            'search' => ['nullable', 'string'],
            'groupId' => ['nullable', 'exists:groups,id'],
            'tags' => ['nullable', 'string'],
            'masteryId' => ['nullable', 'exists:masteries,id'],
            'column' => ['nullable', 'string'],
            'direction' => ['nullable', 'string'],
            'limit' => ['nullable', 'numeric'],
            'page' => ['nullable', 'numeric'],
        ]);

        $learnings = $this->learningService->getPaginatedFiltered($safe, ['mastery:id,name']);

        return $this->json($learnings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $safe = $request->validate([
            'masteryId' => ['nullable', 'exists:masteries,id'],
            'name' => ['required', 'string'],
        ]);

        $learning = $this->learningService->createLearning($safe);

        return $this->json($learning);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $learning = $this->learningService->getLearningById($id);

        return $this->json($learning);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $safe = $request->validate([
            'name' => ['required', 'string'],
        ]);

        try {
            $this->learningService->updateLearning($safe, $id);

            return $this->json("Learning updated successfully");
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
            return $this->jsonException($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->learningService->deleteLearning($id);

        return $this->json("Learning deleted successfully");
    }
}
