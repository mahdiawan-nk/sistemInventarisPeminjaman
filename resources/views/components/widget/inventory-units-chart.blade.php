@props([
    'title' => 'Total Units vs Borrowed Units',
    'totalUnits' => 100,
    'borrowedUnits' => 40,
    'type' => 'donut', // 'donut' atau 'bar'
])

<div class="bg-white dark:bg-neutral-900 shadow rounded-xl p-5">
    {{-- Card Title --}}
    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">{{ $title }}</h3>

    {{-- Badge Summary --}}
    <div class="flex gap-3 mb-4">
        <span
            class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
            Total Units: {{ $totalUnits }}
        </span>
        <span
            class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
            Borrowed Units: {{ $borrowedUnits }}
        </span>
    </div>

    {{-- Chart --}}
    <div id="inventoryUnitsChart" style="height: 300px; width: 87%;"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var chartType = "{{ $type }}" === 'bar' ? 'column' : 'doughnut';

            var chart = new CanvasJS.Chart("inventoryUnitsChart", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "{{ $title }}"
                },
                data: [{
                    type: chartType,
                    showInLegend: true,
                    indexLabel: "{label} - {y}",
                    dataPoints: [{
                            y: {{ $totalUnits }},
                            label: "Total Units",
                            color: "#3b82f6"
                        },
                        {
                            y: {{ $borrowedUnits }},
                            label: "Borrowed Units",
                            color: "#facc15"
                        }
                    ]
                }]
            });
            chart.render();
        });
    </script>
</div>
