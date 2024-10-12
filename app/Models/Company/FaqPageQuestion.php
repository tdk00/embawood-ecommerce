<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqPageQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'faq_page_detail_id', 'question' , 'answer'
    ];

    public function translations()
    {
        return $this->hasMany(FaqPageQuestionTranslation::class);
    }

    public function getQuestionAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->question : $this->attributes['question'];
    }

    public function getAnswerAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->answer : $this->attributes['answer'];
    }

    public function faqPageDetail()
    {
        return $this->belongsTo(FaqPageDetail::class);
    }
}
