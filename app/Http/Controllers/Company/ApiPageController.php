<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Page;
use Illuminate\Http\Request;

class ApiPageController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/company/pages",
     *     operationId="getFooterPages",
     *     tags={"Company"},
     *     summary="Get pages displayed in the footer",
     *     description="Retrieves a list of pages that are configured to appear in the footer, including their title and content.",
     *     @OA\Response(
     *         response=200,
     *         description="Footer pages retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="title", type="string", description="Title of the page", example="Privacy Policy"),
     *                 @OA\Property(property="content", type="string", description="Content of the page", example="We value your privacy and are committed to protecting it.")
     *             )
     *         )
     *     )
     * )
     */

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
