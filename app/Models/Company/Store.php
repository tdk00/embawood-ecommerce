<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'city', 'latitude', 'longitude'];

    // Define the relationship with StorePhoneNumber
    public function phoneNumbers()
    {
        return $this->hasMany(StorePhoneNumber::class);
    }
}
