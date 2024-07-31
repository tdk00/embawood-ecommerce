<?php

namespace App\Models\Bonus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusExecution extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'bonus_id', 'executed_at'];

    public function bonus()
    {
        return $this->belongsTo(Bonus::class);
    }
}
