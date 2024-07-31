<?php

namespace App\Http\Controllers\Ideas;

use App\Http\Controllers\Controller;
use App\Models\Ideas\Idea;
use Illuminate\Http\Request;

class ApiIdeaController extends Controller
{

    public function index()
    {
        $ideas = Idea::with('subIdeas')->get();
        return response()->json($ideas);
    }

    public function show(Idea $idea)
    {
        $idea->load('subIdeas');
        return response()->json($idea);
    }
}
