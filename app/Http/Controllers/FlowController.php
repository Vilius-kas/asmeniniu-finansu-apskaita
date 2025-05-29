<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flow;
use App\Models\Subcategory;

class FlowController extends Controller
{
    public function create()
    {
    $subcategories = Subcategory::with('category')->get();
    return view('flows.create', compact('subcategories'));
    }


    
    public function store(Request $request)
    {
        $request->validate([
            'subcategory_id' => 'required|exists:subcategories,id',
            'amount' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        Flow::create([
            'subcategory_id' => $request->subcategory_id,
            'amount' => $request->amount,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Srautas sėkmingai pridėtas!');
    }

    public function edit(Flow $flow) {
    $subcategories = Subcategory::with('category')->get();
    return view('flows.edit', compact('flow', 'subcategories'));
    }


    public function update(Request $request, Flow $flow) {
    $request->validate([
        'subcategory_id' => 'required|exists:subcategories,id',
        'amount' => 'required|numeric',
        'notes' => 'nullable|string',
    ]);

    $flow->update($request->only(['subcategory_id', 'amount', 'notes']));

    return redirect()->route('flows.index')->with('success', 'Įrašas sėkmingai atnaujintas!');
    }

    public function index()
    {
        $flows = Flow::with('subcategory.category')->orderBy('created_at', 'desc')->get();
        $balance = Flow::getBalance();
        return view('flows.index', compact('flows', 'balance'));
    }

    public function destroy(Flow $flow)
    {
        $flow->delete();
        return redirect()->route('flows.index')->with('success', 'Įrašas sėkmingai ištrintas!');
    }


}
