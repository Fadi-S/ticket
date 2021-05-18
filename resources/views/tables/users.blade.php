@php
        $colors = [
            'deacon-admin' => [
                'border' => 'border-indigo-500 dark:border-indigo-800',
                'background' => 'bg-indigo-200 dark:bg-indigo-800',
                'text' => 'text-indigo-500 dark:text-indigo-300',
            ],
            'super-admin' => [
                'border' => 'border-green-500 dark:border-green-800',
                'background' => 'bg-green-200 dark:bg-green-300',
                'text' => 'text-green-500 dark:text-green-800',
            ],
            'user' => [
                'border' => 'border-gray-500 dark:border-gray-300',
                'background' => 'bg-gray-200 dark:bg-gray-300',
                'text' => 'text-gray-500 dark:text-gray-800',
            ],
            'kashafa' => [
                'border' => 'border-yellow-500 dark:border-yellow-800',
                'background' => 'bg-yellow-200 dark:bg-yellow-800',
                'text' => 'text-yellow-700 dark:text-yellow-300',
            ],
            'agent' => [
                'border' => 'border-red-500 dark:border-red-300',
                'background' => 'bg-red-200 dark:bg-red-300',
                'text' => 'text-red-500 dark:text-red-800',
            ],
            'deacon' => [
                'border' => 'border-blue-500 dark:border-blue-800',
                'background' => 'bg-blue-200 dark:bg-blue-800',
                'text' => 'text-blue-500 dark:text-blue-300',
            ],
        ];
@endphp

@php($color = $colors[isset($row->roles[0]) ? $row->roles[0]->name : 'user'])
<x-table.td>
        <span dir="ltr" class="rtl:text-right text-gray-800 dark:text-gray-200 text-md font-semibold">#{{ $row->id }}</span>
</x-table.td>

<x-table.td>
        <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                        <img class="h-10 w-10 rounded-full" src="{{ $row->picture }}" alt="{{ $row->name }}'s picture">
                </div>
                <div class="ml-4 space-y-2">
                        <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $row->name }}
                                </div>
                                @if($row->activated_at)
                                        <div class="text-xs bg-blue-600 text-white rounded-full mx-1">
                                                <x-svg.check size="w-4 h-4" />
                                        </div>
                                @endif
                        </div>
                        <div class="dark:text-gray-300 font-semibold text-gray-500 text-sm sm:hidden">
                                {{ $row->arabic_name }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $row->email }}
                        </div>
                </div>
        </div>
</x-table.td>

<x-table.td class="hidden sm:table-cell">
        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                {{ $row->arabic_name }}
        </div>
</x-table.td>

<x-table.td>
        <div class="flex items-center justify-start">
                @if($row->isVerified())
                        <div class=" rounded-full p-1 w-8">
                                <x-svg.check class="text-green-500" size=""/>
                        </div>
                @else
                        <div class="rounded-full p-1 w-8"></div>
                @endif
                <span dir="ltr" class="text-gray-800 dark:text-gray-200 text-md font-semibold mx-2">{{ $row->phone }}</span>
        </div>
</x-table.td>

<x-table.td>
        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                {{ $row->national_id }}
        </div>
</x-table.td>

<x-table.td>
        <div class="text-sm font-medium
        {{ $row->gender ? 'text-blue-800 dark:text-blue-600' : 'text-pink-800 dark:text-pink-600' }}
">
                <x-svg.gender :class="!$row->gender ? 'rotate-135' : ''" />
        </div>
</x-table.td>

@if(auth()->user()->can("users.view"))
        <x-table.td>
                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ ($login = $row->logins()->latest('time')->first()) ? $login->time->diffForHumans() : '-' }}
                </div>
        </x-table.td>

        <x-table.td>
                @if($row->creator)
                        <button type="button" wire:click="$set('filters.search', '#{{ $row->creator->id }}')"
                                title="#{{ $row->creator->id }}"
                                class="text-gray-800 dark:text-gray-200 text-md focus:outline-none
                                            font-semibold hover:text-gray-700 dark:hover:text-gray-300">
                                {{ $row->creator->first_name }}
                        </button>
                @else
                        -
                @endif
        </x-table.td>

        <x-table.td>
                <div class="flex items-center justify-start">
                        <div class="font-bold text-sm rounded-full py-1 px-2 text-center
                                            {{ $color['background'] . ' ' . $color['text'] }}">
                                {{ isset($row->roles[0]) ? $row->roles[0]->name : 'user' }}
                        </div>
                </div>
        </x-table.td>
@endif
@if(auth()->user()->can("users.activate"))
        <x-table.td>
                <div class="flex items-center justify-center">
                        @if($row->activated_at === null)
                                <button class="rounded-full p-1 focus:outline-none
                                border border-green-500 hover:bg-green-500 transition-dark
                           dark:hover:bg-green-500 hover:text-white text-green-500 dark:text-green-100"
                                        type="button" wire:click="activate('{{ $row->username }}')">
                                        <x-svg.check wire:loading.remove wire:target="activate('{{ $row->username }}')" />
                                        <x-svg.spinner wire:loading wire:target="activate('{{ $row->username }}')" />
                                </button>
                        @endif
                </div>
        </x-table.td>
@endif
<x-table.td>
        <x-buttons.edit :url="url('/users/' . $row->username . '/edit')" />
</x-table.td>
