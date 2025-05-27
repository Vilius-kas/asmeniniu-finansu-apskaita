@if (session('success'))
    <div>{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('flows.update', $flow) }}">
    @csrf
    @method('PUT')

    <label for="subcategory_id">Subkategorija:</label>
    <select name="subcategory_id" required>
        @foreach($subcategories as $subcategory)
            <option value="{{ $subcategory->id }}" 
                {{ $flow->subcategory_id == $subcategory->id ? 'selected' : '' }}>
                {{ $subcategory->category->name ?? 'Nepriskirta kategorija' }} — {{ $subcategory->name }}
            </option>
        @endforeach
    </select>

    <label for="amount">Suma:</label>
    <input type="number" step="0.01" name="amount" value="{{ $flow->amount }}" required>

    <label for="notes">Pastabos:</label>
    <textarea name="notes">{{ $flow->notes }}</textarea>

    <button type="submit">Atnaujinti</button>
</form>

 