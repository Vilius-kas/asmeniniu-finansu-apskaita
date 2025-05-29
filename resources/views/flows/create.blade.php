@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-6">Naujas įrašas</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('flows.store') }}" method="POST" class="space-y-4">
        @csrf
        
        <!-- TIPAS -->
        <div>
            <label class="block text-sm font-medium mb-2">Tipas</label>
            <select id="type-select" class="w-full border rounded px-3 py-2">
                <option value="">Pasirinkite tipą</option>
                <option value="1">Pajamos</option>
                <option value="-1">Išlaidos</option>
            </select>
        </div>

        <!-- SUBKATEGORIJA -->
        <div>
            <label class="block text-sm font-medium mb-2">Subkategorija</label>
            <select name="subcategory_id" id="subcategory-select" required class="w-full border rounded px-3 py-2">
                <option value="">Pirmiau pasirinkite tipą</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Suma</label>
            <input type="number" name="amount" step="0.01" required class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Pastabos</label>
            <textarea name="notes" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Įrašyti</button>
            <a href="{{ route('flows.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Atšaukti</a>
        </div>
    </form>
</div>

<script>
document.getElementById('type-select').addEventListener('change', function() {
    const type = this.value;
    const subcategorySelect = document.getElementById('subcategory-select');
    
    if (!type) {
        subcategorySelect.innerHTML = '<option value="">Pirmiau pasirinkite tipą</option>';
        return;
    }
    
    fetch(`/subcategories/type/${type}`)
        .then(response => response.json())
        .then(data => {
            subcategorySelect.innerHTML = '<option value="">Pasirinkite subkategoriją</option>';
            data.forEach(subcategory => {
                subcategorySelect.innerHTML += `<option value="${subcategory.id}">${subcategory.category.name} - ${subcategory.name}</option>`;
            });
        });
});
</script>
@endsection
