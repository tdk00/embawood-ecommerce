<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqPageDetailTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'faq_page_detail_id',
        'locale',
        'email_title',
        'email_description',
        'phone_title',
        'phone_description',
    ];

    /**
     * The translation belongs to a FaqPageDetail.
     */
    public function faqPageDetail()
    {
        return $this->belongsTo(FaqPageDetail::class);
    }
}
