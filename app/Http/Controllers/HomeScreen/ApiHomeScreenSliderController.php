<?php

namespace App\Http\Controllers\HomeScreen;

use App\Http\Controllers\Controller;
use App\Models\HomeScreen\HomeScreenSlider;
use Illuminate\Http\Request;

class ApiHomeScreenSliderController extends Controller
{
    public function index()
    {
        $sliders = HomeScreenSlider::where('is_active', true)->with('news')->get();
        return response()->json($sliders);
    }

    public function show($id)
    {
        $slider = HomeScreenSlider::where('is_active', true)->with('news')->findOrFail($id);
        return response()->json($slider);
    }
}
