?>

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6">Ataskaitos</h1>

    <form action="{{ route('reports.generate') }}" method="POST" class="bg-white p-6 rounded-lg shadow">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-2">Ataskaitos tipas</label>
                <select name="report_type" required class="w-full border rounded px-3 py-2">
                    <option value="">Pasirinkite tipą</option>
                    <option value="period">Pagal periodą</option>
                    <option value="category">Pagal kategoriją</option>
                    <option value="analysis">Analizė</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Srautų tipas (nebūtina)</label>
                <select name="flow_type" class="w-full border rounded px-3 py-2">
                    <option value="">Visi</option>
                    <option value="1">Tik pajamos</option>
                    <option value="-1">Tik išlaidos</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Nuo datos</label>
                <input type="date" name="from_date" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Iki datos</label>
                <input type="date" name="to_date" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Kategorija (nebūtina)</label>
                <select name="category_id" class="w-full border rounded px-3 py-2">
                    <option value="">Visos kategorijos</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded">Generuoti ataskaitą</button>
            <a href="{{ route('flows.index') }}" class="ml-4 bg-gray-500 text-white px-6 py-2 rounded">Grįžti</a>
        </div>
    </form>
</div>
@endsection