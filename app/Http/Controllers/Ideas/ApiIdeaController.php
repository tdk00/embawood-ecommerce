<?php

namespace App\Http\Controllers\Ideas;

use App\Http\Controllers\Controller;
use App\Models\Ideas\Idea;
use Illuminate\Http\Request;

class ApiIdeaController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/ideas",
     *     operationId="getAllIdeas",
     *     tags={"Ideas"},
     *     summary="Retrieve all ideas",
     *     description="Fetches a list of all ideas with their associated sub-ideas.",
     *     @OA\Response(
     *         response=200,
     *         description="List of ideas retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", description="Idea ID", example=1),
     *                 @OA\Property(property="title_category_view", type="string", description="Title for category view", example="Living Room Ideas"),
     *                 @OA\Property(property="title_homepage_tab_view", type="string", description="Title for homepage tab view", example="Living Room"),
     *                 @OA\Property(property="is_active", type="boolean", description="Indicates if the idea is active", example=true),
     *                 @OA\Property(
     *                     property="sub_ideas",
     *                     type="array",
     *                     description="List of sub-ideas associated with the idea",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", description="Sub-idea ID", example=1),
     *                         @OA\Property(property="title", type="string", description="Sub-idea title", example="Modern Sofas"),
     *                         @OA\Property(property="image_category_view", type="string", description="Category view image URL", example="http://example.com/storage/images/sofas.jpg"),
     *                         @OA\Property(property="image_homepage_tab_view", type="string", description="Homepage tab view image URL", example="http://example.com/storage/images/tab_view.jpg"),
     *                         @OA\Property(property="is_active", type="boolean", description="Indicates if the sub-idea is active", example=true)
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $ideas = Idea::with('subIdeas')->get();
        return response()->json($ideas);
    }

    /**
     * @OA\Get(
     *     path="/api/ideas/{idea}",
     *     operationId="getIdeaDetails",
     *     tags={"Ideas"},
     *     summary="Retrieve details of a specific idea",
     *     description="Fetches the details of a specific idea along with its associated sub-ideas.",
     *     @OA\Parameter(
     *         name="idea",
     *         in="path",
     *         required=true,
     *         description="ID of the idea",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Idea details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="Idea ID", example=1),
     *             @OA\Property(property="title_category_view", type="string", description="Title for category view", example="Living Room Ideas"),
     *             @OA\Property(property="title_homepage_tab_view", type="string", description="Title for homepage tab view", example="Living Room"),
     *             @OA\Property(property="is_active", type="boolean", description="Indicates if the idea is active", example=true),
     *             @OA\Property(
     *                 property="sub_ideas",
     *                 type="array",
     *                 description="List of sub-ideas associated with the idea",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", description="Sub-idea ID", example=1),
     *                     @OA\Property(property="title", type="string", description="Sub-idea title", example="Modern Sofas"),
     *                     @OA\Property(property="image_category_view", type="string", description="Category view image URL", example="http://example.com/storage/images/sofas.jpg"),
     *                     @OA\Property(property="image_homepage_tab_view", type="string", description="Homepage tab view image URL", example="http://example.com/storage/images/tab_view.jpg"),
     *                     @OA\Property(property="is_active", type="boolean", description="Indicates if the sub-idea is active", example=true)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Idea not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message", example="Idea not found")
     *         )
     *     )
     * )
     */
    public function show(Idea $idea)
    {
        $idea->load('subIdeas');
        return response()->json($idea);
    }
}
