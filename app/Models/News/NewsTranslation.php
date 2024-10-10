<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsTranslation extends Model
{
    protected $table = 'news_translations'; // Specify the table name

    protected $fillable = ['news_id', 'locale', 'title', 'content'];

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
