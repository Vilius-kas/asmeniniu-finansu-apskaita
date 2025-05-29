@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Kategorijos</h1>
        <a href="{{ route('categories.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Pridėti kategoriją</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pavadinimas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subkategorijos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Veiksmai</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($categories as $category)
                <tr>
                    <td class="px-6 py-4">{{ $category->name }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded {{ $category->type == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $category->type_text }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $category->subcategories->count() }}</td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('categories.edit', $category) }}" class="text-blue-600 hover:text-blue-900">Redaguoti</a>
                        <form method="POST" action="{{ route('categories.destroy', $category) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Ar tikrai norite ištrinti?')">Trinti</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection