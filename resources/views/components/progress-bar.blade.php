@props([
    'progress',
    'color' => 'bg-blue-500',
    'secondaryProgress' => 0,
    'secondaryColor' => 'bg-green-500',
])

<div class="shadow w-full bg-gray-200 rounded-lg overflow-hidden">

    <div class="{{ $color }} text-xs leading-none py-1
     text-center text-white" style="width: {{ $progress }}%">{{ $progress }}%</div>

    @if($secondaryProgress)
        <div class="{{ $secondaryColor }} text-xs leading-none py-1
         text-center text-white" style="width: {{ $secondaryProgress }}%"></div>
    @endif

</div>