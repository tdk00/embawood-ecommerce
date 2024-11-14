<?php
// app/Models/AboutUs.php
namespace App\Models\Company;
use Illuminate\Database\Eloquent\Model;

class AboutUsTranslation extends Model
{
    protected $fillable = ['about_us_id', 'locale', 'title', 'description', 'description_web'];

    public function aboutUs()
    {
        return $this->belongsTo(AboutUs::class);
    }
}
