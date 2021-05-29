<x-slot name="title">View All Types | Ticket</x-slot>

<x-card>
    <x-table.table>
        <x-slot name="head">
            <tr>
                <x-table.th>{{ __('Name') }}</x-table.th>
                <x-table.th>{{ __('Shown') }}</x-table.th>
                <x-table.th>{{ __('Color') }}</x-table.th>
                <x-table.th>{{ __('Has Deacons') }}</x-table.th>
                @if(auth()->user()->can('types.edit'))
                    <x-table.th>{{ __('Edit') }}</x-table.th>
                @endif
            </tr>
        </x-slot>

        <x-slot name="body">

            @foreach($types as $type)
                <tr wire:loading.class="opacity-50" wire:key="type-{{ $type->id }}">
                    <x-table.td>
                        <div class="space-y-1">
                            <div class="text-gray-800 dark:text-gray-200 text-lg font-semibold">
                                <a class="text-blue-500 hover:text-blue-600 underline font-semibold"
                                   href="{{ url("/conditions/$type->url") }}">
                                    {{ $type->arabic_name }}
                                </a>
                            </div>

                            <div class="text-gray-600 dark:text-gray-400 font-semibold text-sm">
                                {{ $type->plural_name }}
                            </div>

                            <div class="dark:text-gray-500 font-semibold text-gray-400 text-sm">
                                {{ __('Max Reservations: :max', ['max' => $type->max_reservations]) }}
                            </div>
                        </div>
                    </x-table.td>

                    <x-table.td>
                        <x-svg.active-or-not :active="$type->show" />
                    </x-table.td>

                    <x-table.td>
                        <div class="w-6 h-6 rounded-full p-1 text-center flex items-center justify-center"
                             style="background-color: {{ $type->color }}"
                        >
                        </div>
                    </x-table.td>

                    <x-table.td>
                        <x-svg.active-or-not :active="$type->has_deacons" />
                    </x-table.td>

                    @if(auth()->user()->can('events.edit'))
                        <x-table.td>
                            <x-buttons.edit :url="url('/types/' . $type->url . '/edit')" />
                        </x-table.td>
                    @endif
                </tr>
            @endforeach

        </x-slot>

    </x-table.table>

</x-card>
