<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Models\Category;

class SubcategoryController extends Controller
{

    public function index()
    {
        $subcategories = Subcategory::with('category')->get();
        return view('subcategories.index', compact('subcategories'));
    }


    public function create()
    {
        $categories = Category::all();
        return view('subcategories.create', compact('categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255'
        ]);

        Subcategory::create($request->all());
        return redirect()->route('subcategories.index')->with('success', 'Subkategorija sukurta!');
    }

    public function edit(Subcategory $subcategory)
    {
        $categories = Category::all();
        return view('subcategories.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255'
        ]);

        $subcategory->update($request->all());
        return redirect()->route('subcategories.index')->with('success', 'Subkategorija atnaujinta!');
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();
        return redirect()->route('subcategories.index')->with('success', 'Subkategorija ištrinta!');
    }

    // AJAX metodas filtravimui pagal tipą
    public function getByType($type)
    {
        $subcategories = Subcategory::whereHas('category', function($q) use ($type) {
            $q->where('type', $type);
        })->with('category')->get();

        return response()->json($subcategories);
    }

    // SubcategoryController.php
    public function getByCategory($categoryId)
    {
    $subcategories = Subcategory::where('category_id', $categoryId)->get();
    return response()->json($subcategories);
    }
}
