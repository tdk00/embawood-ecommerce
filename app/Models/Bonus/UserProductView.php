<?php

namespace App\Models\Bonus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProductView extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'viewed_date'];
}
