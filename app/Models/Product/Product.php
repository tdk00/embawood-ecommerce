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


    protected $appends = ['is_in_basket', 'is_favorite', 'final_price', 'remaining_discount_seconds'];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function getMainImageAttribute()
    {
        $mainImage = $this->images()->where('is_main', true)->first();

        // If no main image, fetch the first image
        if (!$mainImage) {
            $mainImage = $this->images()->first();
        }

        // Return the URL/path of the main image or null if no images exist
        return $mainImage ? $mainImage->image_path : null;
    }

    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function getPriceAttribute($value)
    {
        // Check if the product is a set
        if ($this->is_set) {
            // Calculate the sum of the prices of the products within the set, considering the quantity
            return $this->products->sum(function ($product) {
                return $product->price * $product->pivot->quantity;
            });
        }

        // Return the original price if it's not a set
        return $value;
    }

    public function getFinalPriceAttribute()
    {
        if ($this->is_set) {
            // Calculate the sum of the final prices of the products within the set, considering the quantity
            return $this->products->sum(function ($product) {
                return $product->final_price * $product->pivot->quantity;
            });
        }

        $discount = $this->discount;
        if ($discount > 0) {
            return $this->price * (1 - $discount / 100);
        }
        return $this->price;
    }

    public function getDiscountAttribute($value)
    {
        if ($this->is_set) {
            $originalPrice = $this->price;
            $finalPrice = $this->final_price;

            if ($originalPrice > 0) {
                return (($originalPrice - $finalPrice) / $originalPrice) * 100;
            }

            return 0;
        }

        // Check if the discount is active
        if (( $value && $this->discount_ends_at && now()->lessThanOrEqualTo($this->discount_ends_at) ) || ( $value && $this->discount_ends_at === null) ) {
            return $value;
        }

        return 0;
    }


    // Method to check if discount is active
    public function isDiscountActive()
    {
        return $this->discount && ($this->discount_ends_at === null || \Carbon\Carbon::now()->lt($this->discount_ends_at));
    }

    // New method to get remaining seconds until the discount ends
    public function getRemainingDiscountSecondsAttribute()
    {
        if ($this->is_set) {
            // If HasLimitedDiscount is true, return the latest RemainingDiscountSeconds among its products
            if ($this->has_limited_discount) {
                return $this->products->filter(function ($product) {
                    return $product->has_limited_discount;
                })->max(function ($product) {
                    return $product->remaining_discount_seconds;
                });
            }

            return 0; // Return 0 if there is no limited discount
        }

        return $this->has_limited_discount ? \Carbon\Carbon::now()->diffInSeconds($this->discount_ends_at, false) : 0;
    }

    public function getHasUnlimitedDiscountAttribute()
    {
        if ($this->is_set) {
            // Check if any product in the set has an unlimited discount
            foreach ($this->products as $product) {
                if ($product->has_unlimited_discount) {
                    return true;
                }
            }
            return false;
        }

        return $this->discount && $this->discount_ends_at === null;
    }

    public function getHasLimitedDiscountAttribute()
    {
        if ($this->is_set) {
            // If HasUnlimitedDiscount is true, HasLimitedDiscount should be false
            if ($this->has_unlimited_discount) {
                return false;
            }

            // Check if any product in the set has a limited discount
            foreach ($this->products as $product) {
                if ($product->has_limited_discount) {
                    return true;
                }
            }
            return false;
        }

        return $this->discount && $this->discount_ends_at !== null && \Carbon\Carbon::now()->lt($this->discount_ends_at);
    }

    public function getDiscountEndsAtAttribute()
    {
        if ($this->is_set) {
            if ($this->has_unlimited_discount) {
                return null;
            }

            if ($this->has_limited_discount) {
                // Get the latest discount_ends_at among the products with limited discounts
                return $this->products->filter(function ($product) {
                    return $product->has_limited_discount;
                })->max('discount_ends_at');
            }

            return null; // If there is no limited or unlimited discount, return null
        }

        return $this->attributes['discount_ends_at'];
    }

    public function variations()
    {
        return $this->hasMany(Product::class, 'parent_id');
    }

    public function colorVariations()
    {
        return $this->hasMany(Product::class, 'parent_id')->whereNotNull('color');
    }


    public function siblingColorVariations()
    {
        if ($this->parent_id !== null) {
            // If this product is a child, get the parent product and all its color variations
            return Product::where(function($query) {
                $query->where('parent_id', $this->parent_id)
                    ->orWhere('id', $this->parent_id);
            })
                ->whereNotNull('color')
                ->get();
        }

        // If this product is not a child, return an empty collection or null
        return collect();
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
        $identifier = $this->getBasketIdentifier();

        return $this->basketItems()->where('identifier', $identifier)->whereNull('set_id')->exists();
    }

    private function getBasketIdentifier()
    {
        // If the user is authenticated, use their user ID
        if (Auth::guard('api')->check()) {
            return Auth::guard('api')->id();
        }

        return session()->get('basket_identifier');
    }

    public function getIsFavoriteAttribute()
    {
        $userId = Auth::guard('api')->user()?->id;

        return $this->favorites()->where('user_id', $userId)->exists();
    }



    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('status', 'accepted')->avg('rating');
    }

}
