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
            'balansas' => $pajamos + $išlaidos
        ];
    }

    
    public static function reportByPeriod($from, $to, $type = null)
    {
        $query = self::with('subcategory.category')
            ->whereBetween('created_at', [$from, $to]);

        if ($type) {
            $query->whereHas('subcategory.category', function($q) use ($type) {
                $q->where('type', $type);
            });
        }

        return $query->get();
    }

    public static function reportByCategory($categoryId = null)
    {
        $query = self::with('subcategory.category');

        if ($categoryId) {
            $query->whereHas('subcategory', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
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
        ->join('subcategories', 'flows.subcategory_id', '=', 'subcategories.id')
        ->join('categories', 'subcategories.category_id', '=', 'categories.id')
        ->groupBy('subcategories.category_id', 'categories.name')
        ->get();
    }
}

