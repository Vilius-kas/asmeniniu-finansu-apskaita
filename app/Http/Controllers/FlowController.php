<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flow;
use App\Models\Subcategory;
use App\Models\Category;

class FlowController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $subcategories = Subcategory::with('category')->get();
        return view('flows.create', compact('categories', 'subcategories'));
    }


        public function store(Request $request)
    {
    $validated = $request->validate([
        'type' => 'required|in:1,-1',
        'category_id' => 'required|exists:categories,id',
        'subcategory_name' => 'required|string|max:255',
        'amount' => 'required|numeric',
        'notes' => 'nullable|string',
    ]);

    
    $subcategory = Subcategory::firstOrCreate(
    [
        'name' => trim($validated['subcategory_name']),
        'category_id' => $validated['category_id'],
    ]
    );

    
    Flow::create([
        'subcategory_id' => $subcategory->id,
        'amount' => $validated['amount'],
        'notes' => $validated['notes'],
    ]);

    return redirect()->route('dashboard')->with('success', 'Įrašas pridėtas sėkmingai!');
    }



    public function edit(Flow $flow) 
    {
        $categories = Category::all();
        $subcategories = Subcategory::with('category')->get();
        return view('flows.edit', compact('flow', 'categories', 'subcategories'));
    }

    public function update(Request $request, Flow $flow) 
    {
        $request->validate([
            'type' => 'required|in:1,-1',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $subcategoryId = null;

        
        if ($request->subcategory_id) {
            $request->validate([
                'subcategory_id' => 'required|exists:subcategories,id'
            ]);
            $subcategoryId = $request->subcategory_id;
        }
        
        elseif ($request->new_subcategory_name) {
            $request->validate([
                'new_subcategory_name' => 'required|string|max:255'
            ]);
            
            
            $subcategory = Subcategory::create([
                'category_id' => $request->category_id,
                'name' => trim($request->new_subcategory_name)
            ]);
            
            $subcategoryId = $subcategory->id;
        } else {
            return redirect()->back()->withErrors(['subcategory' => 'Pasirinkite subkategoriją arba įrašykite naują.'])->withInput();
        }

        $flow->update([
            'subcategory_id' => $subcategoryId,
            'amount' => $request->amount,
            'notes' => $request->notes,
        ]);

        return redirect()->route('flows.index')->with('success', 'Įrašas sėkmingai atnaujintas!');
    }

    public function index(Request $request)
    {
    $from = $request->input('from_date');
    $to = $request->input('to_date');

    $query = Flow::with('subcategory.category')->orderBy('created_at', 'desc');

    if ($from && $to) {
        $query->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
    }

    $flows = $query->paginate(8);
    $balance = Flow::getBalance();

    return view('flows.index', compact('flows', 'balance', 'from', 'to'));
    }


    public function destroy(Flow $flow)
    {
        $flow->delete();
        return redirect()->route('flows.index')->with('success', 'Įrašas sėkmingai ištrintas!');
    }
}