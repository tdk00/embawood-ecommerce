<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Store;
use App\Models\News\News;
use Illuminate\Http\Request;

class ApiStoresController extends Controller
{
    public function index()
    {
        $news = Store::with('phoneNumbers')->get();
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
