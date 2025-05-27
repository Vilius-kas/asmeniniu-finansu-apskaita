@extends('layouts.app')

@if (session('success'))
    <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@section('content')
<div class="max-w-md mx-auto">
    <form action="{{ route('flows.store') }}" method="POST">
        @csrf
        <label>Subkategorija</label>
        <select name="subcategory_id" required>
        <option disabled selected>Pasirinkite subkategoriją</option>
        @foreach($subcategories as $subcategory)
        <option value="{{ $subcategory->id }}">
            {{ $subcategory->category->name }} - {{ $subcategory->name }}
        </option>
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
