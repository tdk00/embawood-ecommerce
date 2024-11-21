<?php

namespace App\Http\Controllers\Ideas;

use App\Http\Controllers\Controller;
use App\Models\Ideas\IdeaWidgetItem;
use Illuminate\Http\Request;

class ApiIdeaWidgetItemController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/idea-widget-items",
     *     operationId="getAllIdeaWidgetItems",
     *     tags={"IdeaWidgetItems"},
     *     summary="Retrieve all idea widget items",
     *     description="Fetches all idea widget items along with their associated sub-ideas.",
     *     @OA\Response(
     *         response=200,
     *         description="List of idea widget items retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", description="Widget item ID", example=1),
     *                 @OA\Property(property="sort_order", type="integer", description="Sort order of the widget item", example=1),
     *                 @OA\Property(
     *                     property="subIdea",
     *                     type="object",
     *                     description="Associated sub-idea data",
     *                     @OA\Property(property="id", type="integer", description="Sub-idea ID", example=100),
     *                     @OA\Property(property="title", type="string", description="Sub-idea title", example="Modern Living Room"),
     *                     @OA\Property(property="image_homepage_tab_view", type="string", description="URL of the sub-idea image for homepage tab view", example="http://example.com/storage/images/ideas/image1.jpg")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $items = IdeaWidgetItem::with('subIdea')->orderBy('sort_order')->get();
        return response()->json($items);
    }
}
