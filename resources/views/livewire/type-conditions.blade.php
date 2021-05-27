<x-slot name="title">
    View {{ $type->name }} Conditions | Ticket
</x-slot>

<div>
    <x-card>
        <div class="text-2xl font-bold mb-4">
            {{ $type->name }}
        </div>

        <div class="grid grid-cols-4 gap-2">
            <div class="dark:bg-gray-600 bg-gray-100 transition-dark
             col-span-4 sm:col-span-2 rounded flex-col flex items-center">
                <div class="text-lg font-bold">
                    {{ __('Available Conditions') }}
                </div>

                <div class="px-2 py-4 flex flex-col space-y-6">
                    @foreach($conditions as $priority)
                        <div class="flex flex-col space-y-2">
                            @foreach($priority as $condition)
                                <button class="rounded-lg bg-red-300 focus:outline-none
                                 hover:-translate-x-2 transition transform duration-500
                                 text-gray-800 px-4 py-2 text-center" wire:click="toggle('{{ $condition['id'] }}')"
                                     wire:key="condition-av-{{ $condition['id'] }}">
                                    <div wire:loading wire:target="toggle('{{ $condition['id'] }}')">
                                        <x-svg.spinner size="w-3 h-3" class="text-black" />
                                    </div>

                                    {{ $condition['name'] }}
                                </button>
                            @endforeach
                        </div>

                    @endforeach
                </div>
            </div>

            <div class="dark:bg-gray-600 bg-gray-100 transition-dark
             col-span-4 sm:col-span-2 rounded
            flex flex-col flex justify-center items-center">
                <div class="text-lg font-bold">
                    {{ __('Applied Conditions') }}
                </div>

                <div class="px-2 py-4 flex flex-col space-y-6">
                    @foreach($appliedConditions as $priority)
                        <div class="flex flex-col space-y-2">
                            @foreach($priority as $condition)

                                @if($condition['required'])
                                    <div class="rounded-lg bg-green-500
                                     text-gray-800 px-4 py-2 text-center"
                                            wire:key="condition-ap-{{ $condition['id'] }}">
                                        {{ $condition['name'] }}
                                        <span class="text-xs font-semibold ">({{ __('Required') }})</span>
                                    </div>
                                @else
                                    <button class="rounded-lg bg-green-300 focus:outline-none
                                    hover:translate-x-2 transition transform duration-500
                                     text-gray-800 px-4 py-2 text-center" wire:click="toggle('{{ $condition['id'] }}')"
                                         wire:key="condition-ap-{{ $condition['id'] }}">
                                        <div wire:loading wire:target="toggle('{{ $condition['id'] }}')">
                                            <x-svg.spinner size="w-3 h-3" class="text-black" />
                                        </div>

                                        {{ $condition['name'] }}
                                    </button>
                                @endif
                            @endforeach
                        </div>

                    @endforeach
                </div>
            </div>
        </div>
    </x-card>
</div>
