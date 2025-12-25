@props([
    'title' => 'Chart Widget',
    'type' => 'line', // 'line', 'area', 'bar'
    'dataPoints' => null, // array dataPoints
])

@php
    // Data dummy jika tidak diberikan
    if (!$dataPoints) {
        $dataPoints = [
            ['label' => 'Jan', 'y' => 10],
            ['label' => 'Feb', 'y' => 15],
            ['label' => 'Mar', 'y' => 20],
            ['label' => 'Apr', 'y' => 25],
            ['label' => 'May', 'y' => 18],
            ['label' => 'Jun', 'y' => 22],
        ];
    }

    // Konversi type ke CanvasJS
    $chartType = match ($type) {
        'area' => 'area',
        'bar' => 'column',
        default => 'line',
    };
@endphp

<div class="bg-white dark:bg-neutral-900 shadow rounded-xl p-5">
    {{-- Card Title --}}
    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">{{ $title }}</h3>

    {{-- Chart --}}
    <div id="chartWidget" style="height: 300px; width: 87%;"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var chart = new CanvasJS.Chart("chartWidget", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "{{ $title }}"
                },
                axisY: {
                    title: "Count",
                    includeZero: true
                },
                data: [{
                    type: "{{ $chartType }}",
                    color: "#3b82f6",
                    markerSize: 6,
                    lineThickness: 2,
                    dataPoints: {!! json_encode($dataPoints) !!}
                }]
            });
            chart.render();
        });
    </script>
</div>
