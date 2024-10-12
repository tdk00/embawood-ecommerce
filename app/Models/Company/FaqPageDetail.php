<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqPageDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_address',
        'email_title',
        'email_description',
        'phone_number',
        'phone_title',
        'phone_description',
    ];

    public function translations()
    {
        return $this->hasMany(FaqPageDetailTranslation::class);
    }

    public function getEmailTitleAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->email_title : $this->attributes['email_title'];
    }

    public function getEmailDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->email_description : $this->attributes['email_description'];
    }

    public function getPhoneTitleAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->phone_title : $this->attributes['phone_title'];
    }

    public function getPhoneDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->phone_description : $this->attributes['phone_description'];
    }

    public function questions()
    {
        return $this->hasMany(FaqPageQuestion::class);
    }
}
