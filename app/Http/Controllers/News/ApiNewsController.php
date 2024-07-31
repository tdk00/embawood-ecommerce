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
        $news = News::where('is_active', true)->findOrFail($id);
        return response()->json($news);
    }
}
