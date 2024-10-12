<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\AboutUs;
use Illuminate\Http\Request;

class ApiAboutUsController extends Controller
{
    public function index()
    {
        // Fetch the About Us content
        $aboutUs = AboutUs::first();

        // Check if About Us content exists
        if (!$aboutUs) {
            return response()->json(['message' => 'No About Us content available'], 404);
        }

        // Create a response with auto-translated fields using the model's accessors
        $response = [
            'title' => $aboutUs->title,  // Automatically translated
            'description' => $aboutUs->description,  // Automatically translated
        ];

        // Return the response as JSON
        return response()->json($response, 200);
    }
}
