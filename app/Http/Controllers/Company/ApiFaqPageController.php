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
    /**
     * @OA\Get(
     *     path="/api/company/faq-page",
     *     operationId="getFaqPage",
     *     tags={"Company"},
     *     summary="Get FAQ page details",
     *     description="Retrieves FAQ page details including contact information, frequently asked questions, and social media links.",
     *     @OA\Response(
     *         response=200,
     *         description="FAQ details retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string", description="Email address for contact", example="info@company.com"),
     *             @OA\Property(property="email_title", type="string", description="Title for email section", example="Contact Us via Email"),
     *             @OA\Property(property="email_description", type="string", description="Description for email contact", example="We respond within 24 hours."),
     *             @OA\Property(property="phone_number", type="string", description="Phone number for contact", example="+1-800-123-4567"),
     *             @OA\Property(property="phone_title", type="string", description="Title for phone section", example="Call Us"),
     *             @OA\Property(property="phone_description", type="string", description="Description for phone contact", example="Our support team is available 24/7."),
     *             @OA\Property(
     *                 property="questions",
     *                 type="array",
     *                 description="List of frequently asked questions",
     *                 @OA\Items(
     *                     @OA\Property(property="question", type="string", description="FAQ question", example="How can I reset my password?"),
     *                     @OA\Property(property="answer", type="string", description="Answer to the question", example="Click on the 'Forgot Password' link on the login page.")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="social_media_links",
     *                 type="array",
     *                 description="List of social media links",
     *                 @OA\Items(
     *                     @OA\Property(property="icon_path", type="string", description="Path to social media icon", example="https://example.com/storage/images/social_media_icons/facebook.svg"),
     *                     @OA\Property(property="url", type="string", description="Social media URL", example="https://www.facebook.com/company"),
     *                     @OA\Property(property="type", type="string", description="Type of social media", example="facebook")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No FAQ details available",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message", example="No FAQ details available")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $faqPageDetail = FaqPageDetail::with('questions')->first();

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

        return response()->json($response, 200);
    }
}
