<x-master>
    <x-slot name="title">View All Kiahk Events | Ticket</x-slot>

    <x-card>

        <x-table.table>
            <x-slot name="head">
                <tr>
                    <x-table.th>Date</x-table.th>
                    <x-table.th>Time</x-table.th>
                    <x-table.th>Description</x-table.th>
                    <x-table.th>Number of places</x-table.th>
                    <x-table.empty-th>Edit</x-table.empty-th>
                </tr>
            </x-slot>

            <x-slot name="body">

                @foreach($kiahks as $kiahk)
                    <tr>
                        <x-table.td>
                            <a class="text-blue-500 hover:text-blue-600 underline font-semibold"
                               href="{{ url("/tickets/?event=$kiahk->id") }}">{{ $kiahk->start->format("l, dS F Y") }}</a>
                        </x-table.td>

                        <x-table.td>
                            {{ $kiahk->start->format("h:i A") }} - {{ $kiahk->end->format("h:i A") }}
                        </x-table.td>

                        <x-table.td>
                            {{ $kiahk->description }}
                        </x-table.td>

                        <x-table.td>
                            {{ $kiahk->reservedPlaces() }} / {{ $kiahk->number_of_places }}
                        </x-table.td>

                        <x-table.td>
                            <a class="bg-blue-400 px-4 py-2 hover:bg-blue-500
                             text-white text-md rounded-lg"
                               href="{{ url("/kiahk/$kiahk->id/edit") }}">Edit</a>
                        </x-table.td>
                    </tr>
                @endforeach

            </x-slot>

        </x-table.table>

        {{ $kiahks->links() }}

    </x-card>

</x-master>