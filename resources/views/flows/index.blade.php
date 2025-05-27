@extends('layouts.app')
@section('content')
    <h1>Visi srautai</h1>
    <a href="{{ route('flows.create') }}">+ Naujas įrašas</a>

    @if($flows->isEmpty())
        <p>Nėra jokių įrašų.</p>
    @else
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Kategorija</th>
                    <th>Subkategorija</th>
                    <th>Suma</th>
                    <th>Pastabos</th>
                    <th>Veiksmai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($flows as $flow)
                    <tr>
                        <td>{{ $flow->subcategory->category->name ?? '-' }}</td>
                        <td>{{ $flow->subcategory->name ?? '-' }}</td>
                        <td>{{ $flow->amount }}</td>
                        <td>{{ $flow->notes }}</td>
                        <td>
                            <a href="{{ route('flows.edit', $flow->id) }}">✏️ Redaguoti</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
