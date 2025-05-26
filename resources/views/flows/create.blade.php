@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <form action="{{ route('flows.store') }}" method="POST">
        @csrf
        <label>Subkategorija</label>
        <select name="subcategory_id" class="w-full border p-2 mb-4">
            @foreach ($subcategories as $subcategory)
                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
            @endforeach
        </select>

        <label>Suma</label>
        <input type="number" name="amount" step="0.01" class="w-full border p-2 mb-4">

        <label>Pastabos</label>
        <textarea name="notes" class="w-full border p-2 mb-4"></textarea>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2">Įrašyti</button>
    </form>
</div>
@endsection
