<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flow;
use App\Models\Category;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('reports.index', compact('categories'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:period,category,analysis',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'category_id' => 'nullable|exists:categories,id',
            'flow_type' => 'nullable|in:1,-1'
        ]);

        $data = [];
        $categories = Category::all();

        switch ($request->report_type) {
            case 'period':
                $from = $request->from_date ? Carbon::parse($request->from_date) : Carbon::now()->startOfMonth();
                $to = $request->to_date ? Carbon::parse($request->to_date) : Carbon::now()->endOfMonth();
                $data['flows'] = Flow::reportByPeriod($from, $to, $request->flow_type);
                $data['period'] = ['from' => $from, 'to' => $to];
                break;

            case 'category':
                $data['categories'] = Flow::reportByCategory($request->category_id);
                break;

            case 'analysis':
                $flows = Flow::with('subcategory.category');
                if ($request->flow_type) {
                    $flows->whereHas('subcategory.category', function($q) use ($request) {
                        $q->where('type', $request->flow_type);
                    });
                }
                $flowsData = $flows->get();
                
                $data['analysis'] = [
                    'total_count' => $flowsData->count(),
                    'total_amount' => $flowsData->sum('amount'),
                    'min_amount' => $flowsData->min('amount'),
                    'max_amount' => $flowsData->max('amount'),
                    'avg_amount' => $flowsData->avg('amount'),
                ];
                break;
        }

        return view('reports.results', compact('data', 'categories', 'request'));
    }
}
