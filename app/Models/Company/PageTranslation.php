<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model
{
    protected $table = 'page_translations'; // Specify the table name

    protected $fillable = ['page_id', 'locale', 'title', 'content'];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
