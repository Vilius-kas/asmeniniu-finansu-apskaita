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
            <select name="type" id="type-select" required class="w-full border rounded px-3 py-2">
                <option value="">Pasirinkite tipą</option>
                <option value="1">Pajamos</option>
                <option value="-1">Išlaidos</option>
            </select>
        </div>

        <!-- KATEGORIJA -->
        <div id="category-div">
            <label class="block text-sm font-medium mb-2">Kategorija</label>
            <input type="text" name="category_name" id="category-input" placeholder="Įrašykite kategoriją" class="w-full border rounded px-3 py-2" value="{{ old('category_name', $categoryName ?? '') }}" />
        </div>

        <!-- SUBKATEGORIJA -->
        <div id="subcategory-div">
            <label class="block text-sm font-medium mb-2">Subkategorija</label>
            <div class="space-y-2">
                <!-- Pasirinkimas iš esamų -->
                <input type="text" name="subcategory_name" id="subcategory-input" placeholder="Įrašykite subkategoriją" class="w-full border rounded px-3 py-2" value="{{ old('subcategory_name', $subcategoryName ?? '') }}" />

                <!-- Paslėptas laukas subkategorijos ID -->
                <input type="hidden" name="subcategory_id" id="subcategory-id">
            </div>
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
const typeSelect = document.getElementById('type-select');
const categorySelect = document.getElementById('category-select');
const subcategoryDiv = document.getElementById('subcategory-div');
const existingSubcategory = document.getElementById('existing-subcategory');
const newSubcategory = document.getElementById('new-subcategory');
const subcategoryId = document.getElementById('subcategory-id');

// Kai pasirenkamas tipas
typeSelect.addEventListener('change', function() {
    const type = this.value;

    if (!type) {
        categorySelect.innerHTML = '<option value="">Pirmiau pasirinkite tipą</option>';
        // Tik išvalome laukus, nenaikiname subkategorijos div
        subcategoryId.value = '';
        if (existingSubcategory) existingSubcategory.value = '';
        if (newSubcategory) newSubcategory.value = '';
        return;
    }

    fetch(`/categories/type/${type}`)
        .then(response => response.json())
        .then(data => {
            categorySelect.innerHTML = '<option value="">Pasirinkite kategoriją</option>';
            data.forEach(category => {
                categorySelect.innerHTML += `<option value="${category.id}">${category.name}</option>`;
            });
        });

    // Tik išvalome laukus
    subcategoryId.value = '';
    if (existingSubcategory) existingSubcategory.value = '';
    if (newSubcategory) newSubcategory.value = '';
});

// Kai pasirenkama kategorija
categorySelect.addEventListener('change', function () {
    const categoryId = this.value;

    if (!categoryId) {
        // Tik išvalome laukus
        subcategoryId.value = '';
        if (existingSubcategory) existingSubcategory.value = '';
        if (newSubcategory) newSubcategory.value = '';
        return;
    }

    fetch(`/subcategories/category/${categoryId}`)
        .then(response => response.json())
        .then(data => {
            existingSubcategory.innerHTML = '<option value="">Pasirinkite esamą subkategoriją</option>';
            data.forEach(subcategory => {
                existingSubcategory.innerHTML += `<option value="${subcategory.id}">${subcategory.name}</option>`;
            });
        });

    // Išvalome reikšmes
    subcategoryId.value = '';
    if (existingSubcategory) existingSubcategory.value = '';
    if (newSubcategory) newSubcategory.value = '';
});

// Kai pasirenkama esama subkategorija
if (existingSubcategory) {
    existingSubcategory.addEventListener('change', function () {
        if (this.value) {
            subcategoryId.value = this.value;
            if (newSubcategory) newSubcategory.value = '';
        }
    });
}

// Kai įrašoma nauja subkategorija
if (newSubcategory) {
    newSubcategory.addEventListener('input', function () {
        if (this.value.trim()) {
            if (existingSubcategory) existingSubcategory.value = '';
            subcategoryId.value = '';
        }
    });
}
</script>

@endsection