<?php

namespace App\Http\Controllers\Ideas;

use App\Http\Controllers\Controller;
use App\Models\Ideas\Idea;
use App\Models\Ideas\SubIdea;
use Illuminate\Http\Request;

class ApiSubIdeaController extends Controller
{

    public function index()
    {
        $subIdeas = SubIdea::with('subIdeaItems')->get();
        return response()->json($subIdeas);
    }

    public function show(SubIdea $subIdea)
    {
        $subIdea->load('subIdeaItems');
        return response()->json($subIdea);
    }
}
