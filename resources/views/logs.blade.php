<x-master>
    <x-slot name="title">Activity Logs | Ticket</x-slot>

    <x-card>

        <x-table.table>
            <x-slot name="head">
                <tr>
                    <x-table.th>{{ __('Causer') }}</x-table.th>
                    <x-table.th>{{ __('Caused On') }}</x-table.th>
                    <x-table.th>{{ __('Changes') }}</x-table.th>
                </tr>
            </x-slot>

            <x-slot name="body">

                @foreach($logs as $log)
                    <tr>
                        <x-table.td>
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full"
                                         src="{{ ($log->causer) ? $log->causer->picture : "https://bostonglobe-prod.cdn.arcpublishing.com/resizer/EdOe8MZVreAeCmuV2k6btg-LTKA=/420x0/arc-anglerfish-arc2-prod-bostonglobe.s3.amazonaws.com/public/HCIJCIQ2UAI6VKP2L3WHYVGSUE.jpg" }}"
                                         alt="{{ ($log->causer) ? $log->causer->name : "System" }}'s picture">
                                </div>
                                <div class="flex flex-col">
                                    <div>
                                        {{ ($log->causer) ? $log->causer->name : "System" }}
                                    </div>

                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $log->created_at->format('D j M y h:i a') }}
                                    </div>

                                    <div class="text-xs text-gray-500 dark:text-gray-400 font-bold">
                                        {{ $log->description }}
                                    </div>
                                </div>
                            </div>
                        </x-table.td>

                        <x-table.td>
                            <div class="flex flex-col">
                                <div class="font-semibold">
                                    {{ $log->subject_type }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $log->subject ? $log->subject["name"] ?? $log->subject["start"] : '' }}
                                </div>
                            </div>
                        </x-table.td>

                        <x-table.td dir="{{ $dir }}">
                            @if($log->description == 'updated')
                                @foreach($log->changes["old"] as $key => $value)
                                    @if($key == 'password')
                                        @continue
                                    @endif

                                    @php
                                    $date = false;
                                    if(!( is_numeric($value) || is_numeric($log->changes["attributes"][$key]))) {
                                    try{
                                      $old = \Carbon\Carbon::parse($value);
                                      $new = \Carbon\Carbon::parse($log->changes["attributes"][$key]);
                                      $date = $new->year !== 1970;

                                      }catch (Exception $e){}
                                    }
                                    @endphp

                                    <div dir="ltr" class="justify-start items-center flex">
                                        <span class="font-bold text-sm mx-1">{{ $key }}: </span>

                                        <span>
                                            @if($date)
                                                {{ $old->format('d/m/Y h:i a') }} => {{ $new->format('d/m/Y h:i a') }}
                                            @else
                                                {{ $log->changes["old"][$key] }} => {{ $log->changes["attributes"][$key] }}
                                            @endif
                                        </span>
                                    </div>
                                @endforeach

                            @elseif($log->description == 'deleted')
                                <span><pre>{{ print_r($log->changes->get('attributes'), true) }}</pre></span>
                            @elseif($log->description == 'canceled')
                                <span><pre>{{ print_r($log->properties->toArray(), true) }}</pre></span>
                            @endif
                        </x-table.td>
                    </tr>
                @endforeach

            </x-slot>

        </x-table.table>

        {{ $logs->links() }}

    </x-card>

</x-master>
