<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorePhoneNumber extends Model
{
    use HasFactory;

    protected $fillable = ['store_id', 'phone_number'];

    // Define the inverse relationship with Store
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
