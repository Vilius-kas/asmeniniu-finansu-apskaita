<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flow extends Model
{
    protected $fillable = [
    'type',
    'subcategory_id',
    'amount',
    'notes',
    'user_id',
    ];
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    
    public static function getBalance()
    {
        $pajamos = self::whereHas('subcategory.category', function($q) {
            $q->where('type', 1);
        })->sum('amount');

        $išlaidos = self::whereHas('subcategory.category', function($q) {
            $q->where('type', -1);
        })->sum('amount');

    return [
    'pajamos' => $pajamos,
    'išlaidos' => abs($išlaidos),
    'balansas' => $pajamos - abs($išlaidos)
    ];
    }

    
    public static function reportByPeriod($from, $to, $type = null, $categoryId = null)
{
    $query = self::with('subcategory.category')
        ->whereBetween('created_at', [$from, $to]);

    if ($type) {
        $query->whereHas('subcategory.category', function($q) use ($type) {
            $q->where('type', $type);
        });
    }

    if ($categoryId) {
        $query->whereHas('subcategory', function($q) use ($categoryId) {
            $q->where('category_id', $categoryId);
        });
    }

    return $query->orderBy('created_at', 'desc');
    }

    public static function reportByCategory($from = null, $to = null, $type = null, $categoryId = null)
    {
    $query = self::query()
        ->join('subcategories', 'flows.subcategory_id', '=', 'subcategories.id')
        ->join('categories', 'subcategories.category_id', '=', 'categories.id');

    if ($from && $to) {
        $query->whereBetween('flows.created_at', [$from, $to]);
    }

    if ($type) {
        $query->where('categories.type', $type);
    }

    if ($categoryId) {
        $query->where('categories.id', $categoryId);
    }

    return $query->selectRaw('
        subcategories.category_id,
        categories.name as category_name,
        SUM(amount) as total_amount,
        COUNT(*) as count,
        MIN(amount) as min_amount,
        MAX(amount) as max_amount,
        AVG(amount) as avg_amount
    ')
    ->groupBy('subcategories.category_id', 'categories.name')
    ->get();
    }

}

