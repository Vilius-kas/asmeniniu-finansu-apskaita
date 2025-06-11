<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Ataskaita</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1, h2 { margin-bottom: 10px; }
    </style>
</head>
<body>

<h1>Sugeneruota ataskaita</h1>

@if($request->report_type === 'period')
    <h2>Ataskaita pagal periodą</h2>
    @if($flows && count($flows) > 0)
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Tipas</th>
                    <th>Kategorija</th>
                    <th>Subkategorija</th>
                    <th>Suma</th>
                    <th>Pastabos</th>
                </tr>
            </thead>
            <tbody>
                @foreach($flows as $flow)
                    <tr>
                        <td>{{ $flow->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $flow->subcategory->category->type === 1 ? 'Pajamos' : 'Išlaidos' }}</td>
                        <td>{{ $flow->subcategory->category->name }}</td>
                        <td>{{ $flow->subcategory->name }}</td>
                        <td>{{ number_format($flow->amount, 2) }} €</td>
                        <td>{{ $flow->notes }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Šiuo laikotarpiu įrašų nerasta.</p>
    @endif

@elseif($request->report_type === 'category')
    <h2>Ataskaita pagal kategorijas</h2>
    @if($categoryReport && count($categoryReport) > 0)
        <table>
            <thead>
                <tr>
                    <th>Kategorija</th>
                    <th>Įrašų sk.</th>
                    <th>Suma</th>
                    <th>Min</th>
                    <th>Max</th>
                    <th>Vidurkis</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categoryReport as $cat)
                    <tr>
                        <td>{{ $cat->category_name }}</td>
                        <td>{{ $cat->count }}</td>
                        <td>{{ number_format($cat->total_amount, 2) }} €</td>
                        <td>{{ number_format($cat->min_amount, 2) }} €</td>
                        <td>{{ number_format($cat->max_amount, 2) }} €</td>
                        <td>{{ number_format($cat->avg_amount, 2) }} €</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Pagal pasirinktą kategoriją ir laikotarpį įrašų nerasta.</p>
    @endif

@elseif($request->report_type === 'analysis')
    <h2>Analizės ataskaita</h2>
    @if($analysis)
        <table>
            <thead>
                <tr>
                    <th>Iš viso įrašų</th>
                    <th>Bendra suma</th>
                    <th>Min</th>
                    <th>Max</th>
                    <th>Vidurkis</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $analysis['total_count'] }}</td>
                    <td>{{ number_format($analysis['total_amount'], 2) }} €</td>
                    <td>{{ number_format($analysis['min_amount'], 2) }} €</td>
                    <td>{{ number_format($analysis['max_amount'], 2) }} €</td>
                    <td>{{ number_format($analysis['avg_amount'], 2) }} €</td>
                </tr>
            </tbody>
        </table>
        <p style="margin-top: 20px;">(Diagramų palaikymas PDF faile neįgalintas.)</p>
    @else
        <p>Pagal pasirinktą filtrą analizės duomenų nerasta.</p>
    @endif
@endif

</body>
</html>
