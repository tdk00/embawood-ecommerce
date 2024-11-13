<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubcategoryTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'subcategory_id',
        'locale',
        'name',
        'description',
        'meta_title',
        'meta_description',
        'description_web'];

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
}
