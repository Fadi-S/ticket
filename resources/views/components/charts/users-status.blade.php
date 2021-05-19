@props(['id' => rand(1, 100)])

<div class="flex items-center justify-center p-2">
    <div id="chart-{{ $id }}" class="w-64 h-64"></div>
</div>

@push('charts')
<script>
    new Chartisan({
        el: '#chart-{{ $id }}',
        url: "@chart('users_status_chart')",
        hooks: new ChartisanHooks()
            .datasets('pie')
            .legend()
            .pieColors([
                'rgba(30,117,22,0.5)',
                'rgba(12,12,203,0.5)',
            ]),
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
