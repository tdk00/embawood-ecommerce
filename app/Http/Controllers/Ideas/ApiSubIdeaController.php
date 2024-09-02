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
        $subIdeas = SubIdea::with('subIdeaItems.images')->get();

        $transformedSubIdeas = $subIdeas->map(function ($subIdea) {
            $items = $subIdea->subIdeaItems;
            return [
                'id' => $subIdea->id,
                'name' => $subIdea->title,
                'items' => $items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'image' => url('storage/images/ideas/' . $item?->images?->first()?->image_url ?? "") ,
                    ];
                })
            ];
        });

        return response()->json($transformedSubIdeas);
    }

    public function show(SubIdea $subIdea)
    {
        $subIdea->load('subIdeaItems.images');
        $items = $subIdea->subIdeaItems;
        $data =  [
            'id' => $subIdea->id,
            'name' => $subIdea->title,
            'items' => $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'image' => url('storage/images/ideas/' . $item?->images?->first()?->image_url ?? "") ,
                ];
            })
        ];
        return response()->json($data);
    }
}
