<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Page;
use Illuminate\Http\Request;

class ApiPageController extends Controller
{
    public function index()
    {
        $pages = Page::where('show_in_footer', true)->select('id', 'title', 'content')->get();
        $transformedPages = $pages->map(function ($page){
           return [
               'title' => $page->title,
               'content' => $page->content
           ];
        });
        return response()->json($transformedPages);
    }
}
