<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flow;
use App\Models\Category;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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
                $from = $request->from_date 
                    ? Carbon::parse($request->from_date)->startOfDay() 
                    : Carbon::now()->startOfMonth();

                $to = $request->to_date 
                    ? Carbon::parse($request->to_date)->endOfDay() 
                    : Carbon::now()->endOfMonth();

                $data['flows'] = Flow::reportByPeriod($from, $to, $request->flow_type, $request->category_id)->paginate(14);
                $data['period'] = ['from' => $from, 'to' => $to];
                break;

            case 'category':
                $from = $request->from_date ? Carbon::parse($request->from_date)->startOfDay() : null;
                $to = $request->to_date ? Carbon::parse($request->to_date)->endOfDay() : null;

                $data['categories'] = Flow::reportByCategory(
                $from,
                $to,
                $request->flow_type,
                $request->category_id
                );
                break;


            case 'analysis':
                $from = $request->from_date 
                    ? Carbon::parse($request->from_date)->startOfDay() 
                    : Carbon::now()->subDays(7)->startOfDay();
                $to = $request->to_date 
                    ? Carbon::parse($request->to_date)->endOfDay() 
                     : Carbon::now()->endOfDay();

                 $flows = Flow::with('subcategory.category')
                    ->whereBetween('created_at', [$from, $to])
                    ->get();

                 $grouped = $flows->groupBy(function($flow) {
                    return $flow->created_at->format('Y-m-d');
                });

                $chartData = [];

                foreach ($grouped as $date => $items) {
                    $incomes = $items->filter(fn($f) => $f->subcategory->category->type == 1)->sum('amount');
                    $expenses = $items->filter(fn($f) => $f->subcategory->category->type == -1)->sum('amount');

                    $chartData[] = [
                        'date' => $date,
                        'incomes' => $incomes,
                        'expenses' => $expenses
                    ];
                 }

                $data['analysis'] = [
                    'total_count' => $flows->count(),
                    'total_amount' => $flows->sum('amount'),
                    'min_amount' => $flows->min('amount'),
                    'max_amount' => $flows->max('amount'),
                    'avg_amount' => $flows->avg('amount'),
                    'chart' => $chartData
                ];
            break;
    }

        return view('reports.results', [
            'flows' => $data['flows'] ?? null,
            'categories' => $categories,
            'request' => $request,
            'analysis' => $data['analysis'] ?? null,
            'categoryReport' => $data['categories'] ?? null,
            'period' => $data['period'] ?? null,
            'chartData' => $data['chartData'] ?? null,
        ]);
    }
    public function export(Request $request)
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
            $from = $request->from_date 
                ? Carbon::parse($request->from_date)->startOfDay() 
                : Carbon::now()->startOfMonth();

            $to = $request->to_date 
                ? Carbon::parse($request->to_date)->endOfDay() 
                : Carbon::now()->endOfMonth();

            $data['flows'] = Flow::reportByPeriod($from, $to, $request->flow_type, $request->category_id)->get();
            $data['period'] = ['from' => $from, 'to' => $to];
            break;

        case 'category':
            $from = $request->from_date ? Carbon::parse($request->from_date)->startOfDay() : null;
            $to = $request->to_date ? Carbon::parse($request->to_date)->endOfDay() : null;

            $data['categories'] = Flow::reportByCategory(
                $from,
                $to,
                $request->flow_type,
                $request->category_id
            );
            break;

        case 'analysis':
            $from = $request->from_date 
                ? Carbon::parse($request->from_date)->startOfDay() 
                : Carbon::now()->subDays(7)->startOfDay();

            $to = $request->to_date 
                ? Carbon::parse($request->to_date)->endOfDay() 
                : Carbon::now()->endOfDay();

            $flows = Flow::with('subcategory.category')
                ->whereBetween('created_at', [$from, $to])
                ->get();

            $grouped = $flows->groupBy(function($flow) {
                return $flow->created_at->format('Y-m-d');
            });

            $chartData = [];

            foreach ($grouped as $date => $items) {
                $incomes = $items->filter(fn($f) => $f->subcategory->category->type == 1)->sum('amount');
                $expenses = $items->filter(fn($f) => $f->subcategory->category->type == -1)->sum('amount');

                $chartData[] = [
                    'date' => $date,
                    'incomes' => $incomes,
                    'expenses' => $expenses
                ];
            }

            $data['analysis'] = [
                'total_count' => $flows->count(),
                'total_amount' => $flows->sum('amount'),
                'min_amount' => $flows->min('amount'),
                'max_amount' => $flows->max('amount'),
                'avg_amount' => $flows->avg('amount'),
                'chart' => $chartData
            ];
            break;
    }

    $pdf = Pdf::loadView('reports.pdf', [
        'flows' => $data['flows'] ?? null,
        'categories' => $categories,
        'request' => $request,
        'analysis' => $data['analysis'] ?? null,
        'categoryReport' => $data['categories'] ?? null,
        'period' => $data['period'] ?? null,
    ]);

    return $pdf->download('ataskaita.pdf');
}
}
