@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-6">Nauja kategorija</h1>
    
    <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
        @csrf
        
        <div>
            <label class="block text-sm font-medium mb-2">Pavadinimas</label>
            <input type="text" name="name" required class="w-full border rounded px-3 py-2" value="{{ old('name') }}">
            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Tipas</label>
            <select name="type" required class="w-full border rounded px-3 py-2">
                <option value="">Pasirinkite tipą</option>
                <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Pajamos</option>
                <option value="-1" {{ old('type') == '-1' ? 'selected' : '' }}>Išlaidos</option>
            </select>
            @error('type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Sukurti</button>
            <a href="{{ route('categories.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Atšaukti</a>
        </div>
    </form>
</div>
@endsection
