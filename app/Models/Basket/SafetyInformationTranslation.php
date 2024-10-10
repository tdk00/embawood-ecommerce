<?php

namespace App\Models\Basket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafetyInformationTranslation extends Model
{
    protected $fillable = ['safety_information_id', 'locale', 'title', 'description'];

    public function safetyInformation()
    {
        return $this->belongsTo(SafetyInformation::class);
    }
}
