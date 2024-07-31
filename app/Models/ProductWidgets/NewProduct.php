<?php

namespace App\Models\ProductWidgets;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
