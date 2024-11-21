<?php

namespace App\Http\Controllers\Ideas;

use App\Http\Controllers\Controller;
use App\Models\Ideas\Idea;
use App\Models\Ideas\SubIdea;
use Illuminate\Http\Request;

class ApiSubIdeaController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/sub-ideas",
     *     operationId="getAllSubIdeas",
     *     tags={"SubIdeas"},
     *     summary="Retrieve all sub-ideas",
     *     description="Fetches a list of all sub-ideas along with their associated items and the first image of each item.",
     *     @OA\Response(
     *         response=200,
     *         description="List of sub-ideas retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", description="Sub-idea ID", example=1),
     *                 @OA\Property(property="name", type="string", description="Sub-idea title", example="Modern Sofas"),
     *                 @OA\Property(
     *                     property="items",
     *                     type="array",
     *                     description="List of items associated with the sub-idea",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", description="Item ID", example=10),
     *                         @OA\Property(property="image", type="string", description="URL of the item's first image", example="http://example.com/storage/images/ideas/image1.jpg")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $subIdeas = SubIdea::with('subIdeaItems.images')->get();

        $transformedSubIdeas = $subIdeas->map(function ($subIdea) {
            $items = $subIdea->subIdeaItems;
            return [
                'id' => $subIdea->id,
                'name' => $subIdea->title,
                'items' => $items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'image' => url('storage/images/ideas/' . $item?->images?->first()?->image_url ?? "") ,
                    ];
                })
            ];
        });

        return response()->json($transformedSubIdeas);
    }

    /**
     * @OA\Get(
     *     path="/api/sub-ideas/{subIdea}",
     *     operationId="getSubIdeaDetails",
     *     tags={"SubIdeas"},
     *     summary="Retrieve details of a specific sub-idea",
     *     description="Fetches the details of a specific sub-idea along with its associated items and the first image of each item.",
     *     @OA\Parameter(
     *         name="subIdea",
     *         in="path",
     *         required=true,
     *         description="ID of the sub-idea",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sub-idea details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="Sub-idea ID", example=1),
     *             @OA\Property(property="name", type="string", description="Sub-idea title", example="Modern Sofas"),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 description="List of items associated with the sub-idea",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", description="Item ID", example=10),
     *                     @OA\Property(property="image", type="string", description="URL of the item's first image", example="http://example.com/storage/images/ideas/image1.jpg")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sub-idea not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message", example="Sub-idea not found")
     *         )
     *     )
     * )
     */
    public function show(SubIdea $subIdea)
    {
        $subIdea->load('subIdeaItems.images');
        $items = $subIdea->subIdeaItems;
        $data =  [
            'id' => $subIdea->id,
            'name' => $subIdea->title,
            'items' => $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'image' => url('storage/images/ideas/' . $item?->images?->first()?->image_url ?? "") ,
                ];
            })
        ];
        return response()->json($data);
    }
}
