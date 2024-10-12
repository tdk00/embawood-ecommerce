<?php
namespace App\Http\Controllers\Admin\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\FaqPageDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FaqPageDetailController extends Controller
{
    public function edit()
    {
        // Fetch the FaqPageDetail or create a new one if it doesn't exist
        $faqPageDetail = FaqPageDetail::first() ?? FaqPageDetail::create([
                'email_address' => '',
                'email_title' => '',
                'email_description' => '',
                'phone_number' => '',
                'phone_title' => '',
                'phone_description' => ''
            ]);

        return view('admin.pages.company.faq_page_detail.edit', compact('faqPageDetail'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'email_address' => 'required|email',
            'email_title_az' => 'required|string',
            'email_title_en' => 'required|string',
            'email_title_ru' => 'required|string',
            'email_description_az' => 'required|string',
            'email_description_en' => 'required|string',
            'email_description_ru' => 'required|string',
            'phone_number' => 'required|string',
            'phone_title_az' => 'required|string',
            'phone_title_en' => 'required|string',
            'phone_title_ru' => 'required|string',
            'phone_description_az' => 'required|string',
            'phone_description_en' => 'required|string',
            'phone_description_ru' => 'required|string'
        ]);

        // Fetch the only FaqPageDetail record
        $faqPageDetail = FaqPageDetail::first();

        // Update the other fields
        $faqPageDetail->update([
            'email_address' => $validated['email_address'],
            'email_title' => $validated['email_title_az'],
            'email_description' => $validated['email_description_az'],
            'phone_number' => $validated['phone_number'],
            'phone_title' => $validated['phone_title_az'],
            'phone_description' => $validated['phone_description_az'],
        ]);

        // Update or create translations
        foreach (['az', 'en', 'ru'] as $locale) {
            $faqPageDetail->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'email_title' => $validated["email_title_{$locale}"],
                    'email_description' => $validated["email_description_{$locale}"],
                    'phone_title' => $validated["phone_title_{$locale}"],
                    'phone_description' => $validated["phone_description_{$locale}"]
                ]
            );
        }

        return redirect()->back()->with('success', 'FAQ Page updated successfully');
    }
}
