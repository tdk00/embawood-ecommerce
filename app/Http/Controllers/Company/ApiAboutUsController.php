<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\AboutUs;
use Illuminate\Http\Request;

class ApiAboutUsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/company/about-us",
     *     operationId="getAboutUs",
     *     tags={"Company"},
     *     summary="Get About Us content",
     *     description="Retrieves the 'About Us' page content, including the title and description.",
     *     @OA\Response(
     *         response=200,
     *         description="About Us content retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string", description="Title of the About Us section", example="About Our Company"),
     *             @OA\Property(property="description", type="string", description="Detailed description of the company", example="We are a leading company providing exceptional services...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No About Us content available",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message", example="No About Us content available")
     *         )
     *     )
     * )
     */

    public function index()
    {
        $aboutUs = AboutUs::first();

        if (!$aboutUs) {
            return response()->json(['message' => 'No About Us content available'], 404);
        }

        $response = [
            'title' => $aboutUs->title,
            'description' => $aboutUs->description,
        ];

        return response()->json($response, 200);
    }
}
