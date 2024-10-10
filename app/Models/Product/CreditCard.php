<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CreditCard extends Model
{
    use HasFactory;

    protected $fillable = ['credit_cards_image', 'is_active'];

    /**
     * Accessor for credit_cards_image attribute.
     * It returns the full URL for the image stored in the credit_cards folder.
     */
    public function getCreditCardsImageAttribute()
    {
        return url('storage/images/credit_cards/' . $this->attributes['credit_cards_image']);
    }

    /**
     * Query scope for fetching only active credit cards.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Fetch all active credit cards using the scope.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getCreditCards()
    {
        return self::active()->get();
    }
}
