<?php

namespace App\Models\Basket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafetyInformation extends Model
{
    // Explicitly define the correct table name
    protected $table = 'safety_informations';

    protected $fillable = ['title', 'description', 'icon'];
}
