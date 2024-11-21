<?php

namespace App\Http\Controllers\HomeScreen;

use App\Http\Controllers\Controller;
use App\Models\HomeScreen\HomeScreenSlider;
use Illuminate\Http\Request;

class ApiHomeScreenSliderController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/home-screen-sliders",
     *     operationId="getHomeScreenSliders",
     *     tags={"Home Screen Sliders"},
     *     summary="Retrieve all active home screen sliders",
     *     description="Fetches a list of all active sliders on the home screen. Each slider may have an associated news item.",
     *     @OA\Response(
     *         response=200,
     *         description="List of active home screen sliders retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", description="Slider ID", example=1),
     *                 @OA\Property(property="news_id", type="integer", nullable=true, description="Associated news ID", example=2),
     *                 @OA\Property(property="slider_image", type="string", description="Slider image filename", example="slider1.jpg"),
     *                 @OA\Property(property="full_slider_image", type="string", description="Full URL of the slider image", example="http://example.com/storage/images/home_screen/sliders/slider1.jpg"),
     *                 @OA\Property(property="is_active", type="boolean", description="Indicates whether the slider is active", example=true),
     *                 @OA\Property(
     *                     property="news",
     *                     type="object",
     *                     nullable=true,
     *                     description="Associated news item",
     *                     @OA\Property(property="id", type="integer", description="News ID", example=2),
     *                     @OA\Property(property="title", type="string", description="Title of the news", example="Breaking News!"),
     *                     @OA\Property(property="content", type="string", description="Content of the news", example="Detailed news description.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $sliders = HomeScreenSlider::where('is_active', true)->with('news')->get();
        return response()->json($sliders);
    }

    /**
     * @OA\Get(
     *     path="/api/home-screen-sliders/{id}",
     *     operationId="getHomeScreenSliderDetails",
     *     tags={"Home Screen Sliders"},
     *     summary="Retrieve details of a specific home screen slider",
     *     description="Fetches detailed information about a specific home screen slider by its ID, including its associated news item if available.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the home screen slider",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Home screen slider details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="Slider ID", example=1),
     *             @OA\Property(property="news_id", type="integer", nullable=true, description="Associated news ID", example=2),
     *             @OA\Property(property="slider_image", type="string", description="Slider image filename", example="slider1.jpg"),
     *             @OA\Property(property="full_slider_image", type="string", description="Full URL of the slider image", example="http://example.com/storage/images/home_screen/sliders/slider1.jpg"),
     *             @OA\Property(property="is_active", type="boolean", description="Indicates whether the slider is active", example=true),
     *             @OA\Property(
     *                 property="news",
     *                 type="object",
     *                 nullable=true,
     *                 description="Associated news item",
     *                 @OA\Property(property="id", type="integer", description="News ID", example=2),
     *                 @OA\Property(property="title", type="string", description="Title of the news", example="Breaking News!"),
     *                 @OA\Property(property="content", type="string", description="Content of the news", example="Detailed news description.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Slider not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Error message", example="Slider not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $slider = HomeScreenSlider::where('is_active', true)->with('news')->findOrFail($id);
        return response()->json($slider);
    }
}
