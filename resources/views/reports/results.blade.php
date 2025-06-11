@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6">Sugeneruota ataskaita</h1>

    @if($request->report_type === 'period')

        @if($flows && count($flows) > 0)
            <!--ataskaita pagal periodą-->
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded shadow">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">Data</th>
                        <th class="px-4 py-2">Tipas</th>
                        <th class="px-4 py-2">Kategorija</th>
                        <th class="px-4 py-2">Subkategorija</th>
                        <th class="px-4 py-2">Suma</th>
                        <th class="px-4 py-2">Pastabos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($flows as $flow)
                        <tr class="border-b">
                            <td class="px-4 py-2 text-center">{{ $flow->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-2 text-center">{{ $flow->subcategory->category->type === 1 ? 'Pajamos' : 'Išlaidos' }}</td>
                            <td class="px-4 py-2 text-center">{{ $flow->subcategory->category->name }}</td>
                            <td class="px-4 py-2 text-center">{{ $flow->subcategory->name }}</td>
                            <td class="px-4 py-2 text-center">{{ number_format($flow->amount, 2) }} €</td>
                            <td class="px-4 py-2 text-center">{{ $flow->notes }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Šiuo laikotarpiu įrašų nerasta.</p>
        @endif

    @elseif($request->report_type === 'category')
        @if($categoryReport && count($categoryReport) > 0)
            <!--ataskaita pagal kategorijas-->
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded shadow">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">Kategorija</th>
                        <th class="px-4 py-2">Įrašų sk.</th>
                        <th class="px-4 py-2">Suma</th>
                        <th class="px-4 py-2">Min</th>
                        <th class="px-4 py-2">Max</th>
                        <th class="px-4 py-2">Vidurkis</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categoryReport as $cat)
                        <tr class="border-b">
                            <td class="px-4 py-2 text-center">{{ $cat->category_name }}</td>
                            <td class="px-4 py-2 text-center">{{ $cat->count }}</td>
                            <td class="px-4 py-2 text-center">{{ number_format($cat->total_amount, 2) }} €</td>
                            <td class="px-4 py-2 text-center">{{ number_format($cat->min_amount, 2) }} €</td>
                            <td class="px-4 py-2 text-center">{{ number_format($cat->max_amount, 2) }} €</td>
                            <td class="px-4 py-2 text-center">{{ number_format($cat->avg_amount, 2) }} €</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Pagal pasirinktą kategoriją ir laikotarpį įrašų nerasta.</p>
        @endif

        
        @elseif($request->report_type === 'analysis')
        @if(!empty($analysis['chart']))
            <h2 class="text-xl font-semibold mt-8 mb-4">Pajamų ir išlaidų diagrama</h2>
            <canvas id="incomeExpenseChart" height="100"></canvas>


            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
            const chartData = @json($analysis['chart']);

            const labels = chartData.map(row => row.date);
            const incomeData = chartData.map(row => row.incomes);
            const expenseData = chartData.map(row => row.expenses);

            const ctx = document.getElementById('incomeExpenseChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                 labels: labels,
                    datasets: [
                        {
                            label: 'Pajamos',
                            data: incomeData,
                            borderColor: 'green',
                            backgroundColor: 'rgba(0,128,0,0.2)',
                            fill: false
                        },
                       {
                            label: 'Išlaidos',
                            data: expenseData,
                            borderColor: 'red',
                            backgroundColor: 'rgba(255,0,0,0.2)',
                            fill: false
                        }
                    ]
                },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Pajamų ir išlaidų analizė pagal laikotarpį'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
            });
        </script>
        @endif
        @if($analysis)
            <!--analizės ataskaita-->
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded shadow">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">Iš viso įrašų</th>
                        <th class="px-4 py-2">Bendra suma</th>
                        <th class="px-4 py-2">Min</th>
                        <th class="px-4 py-2">Max</th>
                        <th class="px-4 py-2">Vidurkis</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="px-4 py-2 text-center">{{ $analysis['total_count'] }}</td>
                        <td class="px-4 py-2 text-center">{{ number_format($analysis['total_amount'], 2) }} €</td>
                        <td class="px-4 py-2 text-center">{{ number_format($analysis['min_amount'], 2) }} €</td>
                        <td class="px-4 py-2 text-center">{{ number_format($analysis['max_amount'], 2) }} €</td>
                        <td class="px-4 py-2 text-center">{{ number_format($analysis['avg_amount'], 2) }} €</td>
                    </tr>
                </tbody>
            </table>
        @else
            <p>Pagal pasirinktą filtrą analizės duomenų nerasta.</p>
        @endif
    @endif

<div class="mt-6 flex justify-between items-center flex-wrap">
    
    <a href="{{ route('reports.index') }}" class="text-blue-500">← Grįžti atgal</a>

    
    @if($request->report_type === 'period' && $flows && $flows->hasPages())
        <div class="flex justify-center flex-grow">
            {{ $flows->appends(request()->except('page'))->links() }}
        </div>
    @else
        <div class="flex-grow"></div> 
    @endif

    
    @if($request->report_type)
        <a href="{{ route('reports.export', $request->all()) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            ⬇️ Eksportuoti į PDF
        </a>
    @endif
</div>
@endsection
