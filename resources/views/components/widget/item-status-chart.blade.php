@props([
    'title' => 'Item Status Summary', // Judul chart
    'statusCounts' => [
        // Data dummy default
        'Available' => 40,
        'Borrowed' => 25,
        'Maintenance' => 10,
        'Broken' => 5,
        'Lost' => 2,
    ],
])

<div class="bg-white dark:bg-neutral-900 shadow rounded-xl p-5">
    {{-- Card Title --}}
    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">{{ $title }}</h3>

    {{-- Badge Summary --}}
    <div class="flex flex-wrap gap-2 mb-4">
        @foreach ($statusCounts as $status => $count)
            <span
                class="px-3 py-1 rounded-full text-xs font-medium
                @if ($status === 'Available') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                @elseif($status === 'Borrowed') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                @elseif($status === 'Maintenance') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300
                @elseif($status === 'Broken') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                @elseif($status === 'Lost') bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300 @endif
            ">
                {{ $status }}: {{ $count }}
            </span>
        @endforeach
    </div>

    {{-- CanvasJS Chart --}}
    <div id="itemStatusChart" style="height: 300px; width: 86%;"></div>

    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var chart = new CanvasJS.Chart("itemStatusChart", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "{{ $title }}"
                },
                legend: {
                    cursor: "pointer",
                    itemclick: explodePie
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "<b>{label}</b>: {y} units (#percent%)",
                    indexLabel: "{label} - {y}",
                    dataPoints: [{
                            y: {{ $statusCounts['Available'] }},
                            name: "Available",
                            label: 'Available',
                            color: "#22c55e"
                        },
                        {
                            y: {{ $statusCounts['Borrowed'] }},
                            name: "Borrowed",
                            label: 'Borrowed',
                            color: "#facc15"
                        },
                        {
                            y: {{ $statusCounts['Maintenance'] }},
                            name: "Maintenance",
                            label: 'Maintenance',
                            color: "#f97316"
                        },
                        {
                            y: {{ $statusCounts['Broken'] }},
                            name: "Broken",
                            label: 'Broken',
                            color: "#ef4444"
                        },
                        {
                            y: {{ $statusCounts['Lost'] }},
                            name: "Lost",
                            label: 'Lost',
                            color: "#6b7280"
                        }
                    ]
                }]
            });
            chart.render();

            function explodePie(e) {
                if (typeof(e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries
                    .dataPoints[e.dataPointIndex].exploded) {
                    e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
                } else {
                    e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
                }
                e.chart.render();

            }
        });
    </script>
</div>
