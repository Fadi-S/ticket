@props([
    'emptyMessage' => __('No reservations in the last week'),
    'agent',

    'pagination' => false,
])

<x-table.table>
    <x-slot name="head">
        <tr>
            <x-table.th>{{ __('Reserved at') }}</x-table.th>
            <x-table.th>{{ __('Number Of People') }}</x-table.th>
            <x-table.th>{{ __('Event') }}</x-table.th>
        </tr>
    </x-slot>

    <x-slot name="body">

        @php($tickets = $pagination ? $agent->reservedTickets()->paginate(10) : $agent->reservedTickets)

        @forelse($tickets as $ticket)
            <tr>
                <x-table.td>{{ $ticket->reserved_at->format('l, d M Y h:i a') }}</x-table.td>
                <x-table.td>{{ $num->format($ticket->reservations_count) }}</x-table.td>
                <x-table.td>{{ $ticket->event->type->arabic_name }} {{ $ticket->event->eventOrderInDay() }} | {{ $ticket->event->start->format('l d/m') }}</x-table.td>
            </tr>

        @empty
            <tr>
                <x-table.td colspan="3" class="text-center">
                    <span class="text-center text-lg font-bold text-gray-600 dark:text-gray-300">{{ $emptyMessage }}</span>
                </x-table.td>
            </tr>

        @endforelse
    </x-slot>
</x-table.table>

@if($pagination)
    {{ $tickets->links() }}
@endif