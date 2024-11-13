<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\FaqPageDetail;
use App\Models\Company\SocialMedia;
use App\Models\Company\Store;
use App\Models\News\News;
use Illuminate\Http\Request;

class ApiFaqPageController extends Controller
{
    public function index()
    {
        $faqPageDetail = FaqPageDetail::with('questions')->first();

        // Check if the FAQ page detail is available
        if (!$faqPageDetail) {
            return response()->json(['message' => 'No FAQ details available'], 404);
        }

        $socialMediaLinks = SocialMedia::all()->map(function ($socialMedia) {
            return [
                'icon_path' => asset('storage/images/social_media_icons/' . $socialMedia->svg_icon),
                'url' => $socialMedia->url,
                'type' => $socialMedia->type,
            ];
        });

        // Create a response with only the translated fields
        $response = [
            'email' => $faqPageDetail->email_address,
            'email_title' => $faqPageDetail->email_title,
            'email_description' => $faqPageDetail->email_description,
            'phone_number' => $faqPageDetail->phone_number,
            'phone_title' => $faqPageDetail->phone_title,
            'phone_description' => $faqPageDetail->phone_description,
            'questions' => $faqPageDetail->questions->map(function ($question){
                  return [
                      'question' => $question->question,
                      'answer' => $question->answer,
                  ];
            }),
            'social_media_links' => $socialMediaLinks,
        ];

        // Return the response as JSON
        return response()->json($response, 200);
    }
}
