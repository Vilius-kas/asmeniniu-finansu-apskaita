@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Finansų srautai</h1>
        <a href="{{ route('flows.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Pridėti įrašą</a>
    </div>

    <!-- BALANSAS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-100 p-4 rounded">
            <h3 class="font-medium text-blue-800">Balansas</h3>
            <p class="text-2xl font-bold text-blue-900">{{ number_format($balance['balansas'], 2) }} €</p>
        </div>
        <div class="bg-green-100 p-4 rounded">
            <h3 class="font-medium text-green-800">Pajamos</h3>
            <p class="text-2xl font-bold text-green-900">{{ number_format($balance['pajamos'], 2) }} €</p>
        </div>
        <div class="bg-red-100 p-4 rounded">
            <h3 class="font-medium text-red-800">Išlaidos</h3>
            <p class="text-2xl font-bold text-red-900">{{ number_format($balance['išlaidos'], 2) }} €</p>
        </div>
        <div class="bg-gray-100 p-4 rounded">
            <a href="{{ route('reports.index') }}" class="text-gray-800 hover:text-gray-900">
                <h3 class="font-medium">Ataskaitos</h3>
                <p class="text-sm">Žiūrėti detaliau →</p>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategorija</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subkategorija</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Suma</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pastabos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Veiksmai</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($flows as $flow)
                <tr>
                    <td class="px-6 py-4">{{ $flow->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded {{ $flow->subcategory->category->type == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $flow->subcategory->category->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $flow->subcategory->name }}</td>
                    <td class="px-6 py-4 font-mono {{ $flow->subcategory->category->type == 1 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($flow->amount, 2) }} €
                    </td>

                    <td class="px-6 py-4">{{ $flow->notes }}</td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('flows.edit', $flow) }}" class="text-blue-600 hover:text-blue-900">Redaguoti</a>
                        <form method="POST" action="{{ route('flows.destroy', $flow) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Ar tikrai norite ištrinti?')">🗑 Trinti</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection