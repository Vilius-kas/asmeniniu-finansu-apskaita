<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flow extends Model
{
    protected $fillable = ['subcategory_id', 'amount', 'notes'];

    public function subcategory()
{
    return $this->belongsTo(Subcategory::class);
}
}
