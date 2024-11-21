<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\News\News;
use Illuminate\Http\Request;

class ApiNewsController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/news",
     *     operationId="getNewsList",
     *     tags={"News"},
     *     summary="Retrieve all active news",
     *     description="Fetches a list of all active news items.",
     *     @OA\Response(
     *         response=200,
     *         description="List of active news items retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", description="News ID", example=1),
     *                 @OA\Property(property="title", type="string", description="Title of the news", example="Breaking News!"),
     *                 @OA\Property(property="content", type="string", description="Content of the news", example="Detailed description of the news."),
     *                 @OA\Property(property="is_active", type="boolean", description="Status of the news", example=true),
     *                 @OA\Property(property="banner_image", type="string", description="Path to the banner image", example="news-banner.jpg"),
     *                 @OA\Property(property="created_at", type="string", format="datetime", description="Date and time when the news was created", example="2024-11-20T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="datetime", description="Date and time when the news was last updated", example="2024-11-20T12:30:00Z")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $news = News::where('is_active', true)->get();
        return response()->json($news);
    }

    /**
     * @OA\Get(
     *     path="/api/news/{id}",
     *     operationId="getNewsDetails",
     *     tags={"News"},
     *     summary="Retrieve details of a specific news item",
     *     description="Fetches detailed information of a specific news item by its ID, if it is active.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the news item",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="News item details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="News ID", example=1),
     *             @OA\Property(property="name", type="string", description="Title of the news", example="Breaking News!"),
     *             @OA\Property(property="description", type="string", description="Content of the news", example="Detailed description of the news."),
     *             @OA\Property(property="image", type="string", description="URL of the banner image", example="http://example.com/storage/images/news/news-banner.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="News item not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message", example="News item not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        // Find the news item by id and check if it's active
        $news = News::where('is_active', true)->find($id);

        // If the news item is not found, return a JSON response with an error message
        if (!$news) {
            return response()->json([
                'message' => 'News item not found',
            ], 404); // 404 Not Found
        }


        $news->banner_image = url('storage/images/news/' . $news->banner_image);
        $newsDetails = [
            'id' => $news->id,
            'name' => $news->title,
            'description' => $news->content,
            'image' => $news->banner_image,
        ];

        return response()->json($newsDetails);
    }
}
