<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\News\News;
use Illuminate\Http\Request;

class ApiNewsController extends Controller
{
    public function index()
    {
        $news = News::where('is_active', true)->get();
        return response()->json($news);
    }

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
