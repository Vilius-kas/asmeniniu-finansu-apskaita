<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'type']; 
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

   
    public function getTypeTextAttribute()
    {
        return $this->type == 1 ? 'Pajamos' : 'Išlaidos';
    }

    
    public static function pajamos()
    {
        return self::where('type', 1)->get();
    }

    public static function išlaidos()
    {
        return self::where('type', -1)->get();
    }
}
