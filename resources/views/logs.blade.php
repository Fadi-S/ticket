<x-master>
    <x-slot name="title">Activity Logs | Ticket</x-slot>

    <x-card>

        <x-table.table>
            <x-slot name="head">
                <tr>
                    <x-table.th>Picture</x-table.th>
                    <x-table.th>Action</x-table.th>
                    <x-table.th>Changes</x-table.th>
                    <x-table.empty-th>Restore</x-table.empty-th>
                </tr>
            </x-slot>

            <x-slot name="body">

                @foreach($logs as $log)
                    <tr>
                        <x-table.td>
                            <img width="70" class="rounded-full"
                                 src="{{ ($log->causer) ? $log->causer->picture : "https://bostonglobe-prod.cdn.arcpublishing.com/resizer/EdOe8MZVreAeCmuV2k6btg-LTKA=/420x0/arc-anglerfish-arc2-prod-bostonglobe.s3.amazonaws.com/public/HCIJCIQ2UAI6VKP2L3WHYVGSUE.jpg" }}"
                                 alt="{{ ($log->causer) ? $log->causer->name : "System" }}'s picture">
                        </x-table.td>

                        <x-table.td>
                            <strong>{{ ($log->causer) ? $log->causer->name : "System" }}</strong>
                            {{ $log->description }}
                            <strong>{{ $log->subject ? $log->subject["name"] ?? $log->subject["start"] : '' }}</strong>
                            {{ $log->created_at->diffforhumans() }}
                        </x-table.td>

                        <x-table.td>
                            @if($log->description == 'updated')
                            <span>Old: <pre>{{ print_r($log->changes["old"], true) }}</pre></span>
                            <br>
                            <span>New: <pre>{{ print_r($log->changes["attributes"], true) }}</pre></span>
                            @endif
                        </x-table.td>

                        <x-table.td>
                            <a class="bg-blue-400 px-4 py-2 hover:bg-blue-500
                             text-white text-md rounded-lg"
                               href="{{ url("/logs/$log->id/restore") }}">Restore</a>
                        </x-table.td>
                    </tr>
                @endforeach

            </x-slot>

        </x-table.table>

        {{ $logs->links() }}

    </x-card>

</x-master>
