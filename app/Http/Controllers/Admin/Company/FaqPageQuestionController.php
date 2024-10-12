<?php
namespace App\Http\Controllers\Admin\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\FaqPageDetail;
use App\Models\Company\FaqPageQuestion;
use Illuminate\Http\Request;

class FaqPageQuestionController extends Controller
{
    public function index()
    {
        $faqPageDetail = FaqPageDetail::first();
        $faqPageQuestions = FaqPageQuestion::with('translations')->where('faq_page_detail_id', $faqPageDetail->id)->get();

        return view('admin.pages.company.faq_page_questions.index', compact('faqPageQuestions'));
    }

    public function create()
    {
        $faqPageDetail = FaqPageDetail::first(); // Since there is only one FaqPageDetail

        return view('admin.pages.company.faq_page_questions.create', compact('faqPageDetail'));
    }

    public function store(Request $request)
    {
        $faqPageDetail = FaqPageDetail::first(); // Retrieve the only FaqPageDetail

        $validated = $request->validate([
            'question_az' => 'required|string',
            'question_en' => 'required|string',
            'question_ru' => 'required|string',
            'answer_az' => 'required|string',
            'answer_en' => 'required|string',
            'answer_ru' => 'required|string',
        ]);

        $faqPageQuestion = FaqPageQuestion::create([
            'faq_page_detail_id' => $faqPageDetail->id,
            'question' => $validated['question_az'],
            'answer' => $validated['answer_az'],
        ]);

        // Store translations
        $faqPageQuestion->translations()->createMany([
            [
                'locale' => 'az',
                'question' => $validated['question_az'],
                'answer' => $validated['answer_az'],
            ],
            [
                'locale' => 'en',
                'question' => $validated['question_en'],
                'answer' => $validated['answer_en'],
            ],
            [
                'locale' => 'ru',
                'question' => $validated['question_ru'],
                'answer' => $validated['answer_ru'],
            ]
        ]);

        return redirect()->route('admin.faq-page-questions.index')->with('success', 'FAQ Question created successfully');
    }

    public function edit(FaqPageQuestion $faqPageQuestion)
    {
        $faqPageDetail = FaqPageDetail::first();

        return view('admin.pages.company.faq_page_questions.edit', compact('faqPageQuestion', 'faqPageDetail'));
    }

    public function update(Request $request, FaqPageQuestion $faqPageQuestion)
    {
        $validated = $request->validate([
            'question_az' => 'required|string',
            'question_en' => 'required|string',
            'question_ru' => 'required|string',
            'answer_az' => 'required|string',
            'answer_en' => 'required|string',
            'answer_ru' => 'required|string',
        ]);

        // Update translations
        foreach (['az', 'en', 'ru'] as $locale) {
            $faqPageQuestion->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'question' => $validated["question_{$locale}"],
                    'answer' => $validated["answer_{$locale}"]
                ]
            );
        }

        return redirect()->route('admin.faq-page-questions.index')->with('success', 'FAQ Question updated successfully');
    }

    public function destroy(FaqPageQuestion $faqPageQuestion)
    {
        $faqPageQuestion->delete();

        return redirect()->route('admin.faq-page-questions.index')->with('success', 'FAQ Question deleted successfully');
    }
}
