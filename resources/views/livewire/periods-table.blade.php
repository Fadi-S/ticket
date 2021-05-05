<x-slot name="title">View All Periods | Ticket</x-slot>

<x-card>


    <x-table.table>
        <x-slot name="head">
            <tr>
                <x-table.th>{{ __('Name') }}</x-table.th>
                <x-table.th>{{ __('Start') }}</x-table.th>
                <x-table.th>{{ __('End') }}</x-table.th>
                @if(auth()->user()->can('events.edit'))
                    <x-table.th>{{ __('Edit') }}</x-table.th>
                @endif
            </tr>
        </x-slot>

        <x-slot name="body">

            @foreach($periods as $period)
                <tr wire:loading.class="opacity-50" wire:key="period-{{ $period->id }}">
                    <x-table.td>
                        <span class="text-gray-800 dark:text-gray-200 text-md font-semibold">
                            {{ $period->name }}
                        </span>
                    </x-table.td>

                    <x-table.td>
                        <span class="text-gray-800 dark:text-gray-200 text-md font-semibold">
                            {{ $period->start->format('d/m/Y') }}
                        </span>
                    </x-table.td>

                    <x-table.td>
                        <span class="text-gray-800 dark:text-gray-200 text-md font-semibold">
                           {{ $period->start->format('d/m/Y') }}
                        </span>
                    </x-table.td>


                    @if(auth()->user()->can('events.edit'))
                        <x-table.td>
                            <x-buttons.edit :url="url('/periods/' . $period->id . '/edit')" />
                        </x-table.td>
                    @endif
                </tr>
            @endforeach

        </x-slot>

    </x-table.table>

    {{ $periods->links() }}

</x-card>
