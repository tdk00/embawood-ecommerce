<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqPageQuestionTranslation extends Model
{
    protected $table = 'faq_page_question_translations'; // Specify the table name

    protected $fillable = ['faq_page_question_id', 'locale', 'question', 'answer'];

    public function faqPageQuestion()
    {
        return $this->belongsTo(FaqPageQuestion::class);
    }
}
