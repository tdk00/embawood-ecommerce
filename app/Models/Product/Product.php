<?php

namespace App\Models\Product;

use App\Models\Basket\BasketItem;
use App\Models\Category\Subcategory;
use App\Models\Category\TopList;
use App\Models\Ideas\SubIdea;
use App\Models\Ideas\SubIdeaItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id', 'name', 'sku', 'description', 'price', 'stock', 'discount', 'discount_ends_at', 'is_set'
    ];


    protected $appends = ['is_in_basket', 'is_favorite'];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function getFinalPriceAttribute()
    {
        $discount = $this->discount ?? 0;
        return $this->price * (1 - $discount / 100);
    }

    public function isDiscountActive()
    {
        return $this->discount && $this->discount_ends_at && \Carbon\Carbon::now()->lt($this->discount_ends_at);
    }

    public function variations()
    {
        return $this->hasMany(Product::class, 'parent_id');
    }

    public function colorVariations()
    {
        return $this->hasMany(Product::class, 'parent_id')->whereNotNull('color');
    }

    public function parent()
    {
        return $this->belongsTo(Product::class, 'parent_id');
    }

    public function scopeMain($query)
    {
        return $query->whereNull('parent_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_set', 'set_id', 'product_id')->withPivot('quantity');
    }

    public function similarProducts()
    {
        return $this->belongsToMany(Product::class, 'product_similar', 'product_id', 'similar_product_id');
    }

    public function purchasedTogetherProducts()
    {
        return $this->belongsToMany(Product::class, 'product_purchased_together', 'product_id', 'purchased_together_product_id');
    }

    public function subcategories()
    {
        return $this->belongsToMany(Subcategory::class);
    }

    public function subIdeaItems()
    {
        return $this->belongsToMany(SubIdeaItem::class, 'product_sub_idea_item');
    }

    public function topLists()
    {
        return $this->hasMany(TopList::class);
    }

    public function basketItems()
    {
        return $this->hasMany(BasketItem::class, 'product_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function getIsInBasketAttribute()
    {
        $userId = Auth::guard('api')?->user()?->id;

        return $this->basketItems()->where('user_id', $userId)->whereNull('set_id')->exists();
    }

    public function getIsFavoriteAttribute()
    {
        $userId = Auth::guard('api')->user()?->id;

        return $this->favorites()->where('user_id', $userId)->exists();
    }

}
