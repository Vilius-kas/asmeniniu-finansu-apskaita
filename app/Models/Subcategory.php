<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $fillable = ['category_id', 'name'];

    public function category()
{
    return $this->belongsTo(Category::class);
}

public function flows()
{
    return $this->hasMany(Flow::class);
}
}
