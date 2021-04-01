<div>
    @if(!$show)
        <x-button type="button" wire:click="showData" class="mx-6">
            <x-slot name="svg">
                <x-svg.eye wire:loading.remove wire:target="showData"  />

                <x-svg.spinner wire:loading wire:target="showData" />
            </x-slot>

            {{ __('Show Agents Data') }}
        </x-button>
    @endif

    @if($show)
        <x-card>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($agents as $agent)
                <div class="col-span-1 text-center">
                    <span class="font-bold text-2xl text-center">{{ $agent->locale_name }}</span>
                    <a class="text-sm text-blue-600 dark:text-blue-300 hover:text-blue-500 dark:hover:text-blue-400" href="{{ url("/users/$agent->username") }}">{{ __('View All Reservations by this agent') }}</a>

                    <x-agent-status :agent="$agent" />
                </div>
            @endforeach
        </div>

    </x-card>
    @endif
</div>