<style>
    .alert {
        -webkit-border-radius:8px;
        -moz-border-radius:8px;
        border-radius: 8px;
        position: relative;
        margin: 10px 0;
    }
</style>
<div style="display: block; position: fixed; right: 15px; bottom: 15px; z-index: 10000;">

    @php
    $colors = [
        'success' => 'bg-green-50 text-green-800',
        'warning' => 'bg-yellow-50 text-yellow-800 border-yellow-600',
        'info' => 'bg-blue-50 text-blue-800 border-blue-600',
        'danger' => 'bg-red-50 text-red-800 border-red-600'
    ];
    @endphp

    <div class="space-y-2">
        @foreach (session('flash_notification', collect())->toArray() as $key => $message)

                @php($color = $colors[$message['level']])

                <div x-data="{ show: true }"
                     x-show="show"
                     x-init="setTimeout(function() { show=false; }, 5000 * {{ ($key + 1) }});"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-90"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-90"
                     id="alert_flash" class="rounded-md {{ $color }} p-4 {{ $message['important'] ? 'alert-important' : '' }}">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            @switch($message['level'])

                                @case('success')
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                @break

                                @case('warning')
                                <svg class="h-5 w-5 text-yellow-400" x-description="Heroicon name: exclamation" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                @break

                                @case('info')
                                <svg class="h-5 w-5 text-blue-400" x-description="Heroicon name: information-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                @break
                                @case('danger')
                                <svg class="h-5 w-5 text-red-400" x-description="Heroicon name: x-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                @break
                            @endswitch
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium {{ $color }}">
                                {!! $message['message'] !!}
                            </p>
                        </div>
                        @if ($message['important'])
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button @click="show=false" class="inline-flex {{ $color }} rounded-md p-1.5 focus:outline-none">
                                        <span class="sr-only">Dismiss</span>
                                        <!-- Heroicon name: x -->
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
        @endforeach
    </div>
</div>
{{ session()->forget('flash_notification') }}