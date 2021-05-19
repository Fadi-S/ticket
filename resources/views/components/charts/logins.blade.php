@props(['id' => rand(1, 100)])

<div class="flex items-center justify-center p-2 w-full h-full">
    <div id="chart-{{ $id }}" class="w-full h-full"></div>
</div>

@push('charts')
    <script>
        let cha = new Chartisan({
            el: '#chart-{{ $id }}',
            url: "@chart('logins_chart')",
            hooks: new ChartisanHooks()
                .datasets([{
                    type: 'line',
                    fill: true,
                    borderColor: '#1538a0',
                    tension: 0
                }])
                .beginAtZero()
                .colors(['rgba(59,97,224,0.3)'])
                .legend(false),
            loader: {
                color: 'rgba(2,2,47,0.82)',
                size: [30, 30],
                type: 'bar',
                textColor: '#0f2ccb',
                text: 'Loading some chart data...',
            },
        });
    </script>
@endpush
