<?php

namespace App\Http\Controllers\Ideas;

use App\Http\Controllers\Controller;
use App\Models\Ideas\IdeaWidgetTab;
use Illuminate\Http\Request;

class ApiIdeaWidgetTabController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/idea-widget-tabs",
     *     operationId="getAllIdeaWidgetTabs",
     *     tags={"IdeaWidgetTabs"},
     *     summary="Retrieve all idea widget tabs",
     *     description="Fetches all idea widget tabs along with their associated idea and widget items. Each widget item includes a sub-idea with its image.",
     *     @OA\Response(
     *         response=200,
     *         description="List of idea widget tabs retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", description="Idea widget tab ID", example=1),
     *                 @OA\Property(property="sort_order", type="integer", description="Sort order of the widget tab", example=1),
     *                 @OA\Property(
     *                     property="idea",
     *                     type="object",
     *                     description="Associated idea data",
     *                     @OA\Property(property="id", type="integer", description="Idea ID", example=100),
     *                     @OA\Property(property="title_category_view", type="string", description="Category view title of the idea", example="Modern Home Decor")
     *                 ),
     *                 @OA\Property(
     *                     property="ideaWidgetItems",
     *                     type="array",
     *                     description="List of widget items associated with the tab",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", description="Widget item ID", example=200),
     *                         @OA\Property(
     *                             property="subIdea",
     *                             type="object",
     *                             description="Associated sub-idea data",
     *                             @OA\Property(property="id", type="integer", description="Sub-idea ID", example=300),
     *                             @OA\Property(property="name", type="string", description="Sub-idea title", example="Living Room Ideas"),
     *                             @OA\Property(property="image_homepage_tab_view", type="string", description="URL of the sub-idea image for homepage tab view", example="http://example.com/storage/images/ideas/image1.jpg")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $tabs = IdeaWidgetTab::with([
            'idea',
            'ideaWidgetItems' => function ($query) {
                $query->with(['subIdea']);
            }
        ])->orderBy('sort_order')->get();

        // Map through each tab to add the full image URL for each subIdea
        $tabs->map(function ($tab) {
            $tab->ideaWidgetItems->map(function ($item) {
                if (isset($item->subIdea->image_homepage_tab_view)) {
                    $item->subIdea->image_homepage_tab_view = url('storage/images/ideas/' . $item->subIdea->image_homepage_tab_view);
                }
                return $item;
            });
            return $tab;
        });

        return response()->json($tabs);
    }
}
