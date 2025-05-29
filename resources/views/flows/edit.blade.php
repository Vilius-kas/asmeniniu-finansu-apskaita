@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-6">Redaguoti įrašą</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('flows.update', $flow) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        
        <!-- TIPAS -->
        <div>
            <label class="block text-sm font-medium mb-2">Tipas</label>
            <select name="type" id="type-select" required class="w-full border rounded px-3 py-2">
                <option value="">Pasirinkite tipą</option>
                <option value="1" {{ $flow->subcategory->category->type == 1 ? 'selected' : '' }}>Pajamos</option>
                <option value="-1" {{ $flow->subcategory->category->type == -1 ? 'selected' : '' }}>Išlaidos</option>
            </select>
        </div>

        <!-- KATEGORIJA -->
        <div id="category-div">
            <label class="block text-sm font-medium mb-2">Kategorija</label>
            <select name="category_id" id="category-select" required class="w-full border rounded px-3 py-2">
                <option value="">Pasirinkite kategoriją</option>
                @foreach($categories as $category)
                    @if($category->type == $flow->subcategory->category->type)
                        <option value="{{ $category->id }}" 
                            {{ $flow->subcategory->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <!-- SUBKATEGORIJA -->
        <div id="subcategory-div">
            <label class="block text-sm font-medium mb-2">Subkategorija</label>
            <div class="space-y-2">
                <!-- Pasirinkimas iš esamų -->
                <select id="existing-subcategory" class="w-full border rounded px-3 py-2">
                    <option value="">Pasirinkite esamą subkategoriją</option>
                    @foreach($subcategories as $subcategory)
                        @if($subcategory->category_id == $flow->subcategory->category_id)
                            <option value="{{ $subcategory->id }}" 
                                {{ $flow->subcategory_id == $subcategory->id ? 'selected' : '' }}>
                                {{ $subcategory->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
                
                <!-- ARBA įrašymas naujos -->
                <div class="text-center text-gray-500">arba</div>
                
                <input type="text" 
                       name="new_subcategory_name" 
                       id="new-subcategory" 
                       placeholder="Įrašykite naują subkategorijos pavadinimą" 
                       class="w-full border rounded px-3 py-2">
                
                <!-- Paslėptas laukas subkategorijos ID -->
                <input type="hidden" name="subcategory_id" id="subcategory-id" value="{{ $flow->subcategory_id }}">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Suma</label>
            <input type="number" name="amount" step="0.01" value="{{ $flow->amount }}" required class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Pastabos</label>
            <textarea name="notes" class="w-full border rounded px-3 py-2">{{ $flow->notes }}</textarea>
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Atnaujinti</button>
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
typeSelect.addEventListener('change', function () {
    const type = this.value;

    if (!type) {
        categorySelect.innerHTML = '<option value="">Pasirinkite tipą</option>';
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

    // Išvalome subkategorijas
    subcategoryId.value = '';
    if (existingSubcategory) existingSubcategory.value = '';
    if (newSubcategory) newSubcategory.value = '';
});

// Kai pasirenkama kategorija
categorySelect.addEventListener('change', function () {
    const categoryId = this.value;

    if (!categoryId) {
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