<div class="space-y-6">
    <x-form.group class="md:flex-row flex-col md:space-x-2 md:space-y-0 space-x-0 space-y-2">

        <x-form.select name="users[]" :multiple="true" size="md:w-1/2 w-full"
                       id="user" :checked="$create ? (auth()->user()->isUser() ? [auth()->id()] : []) : [$reservation->user->id]"
                       label="Choose Users" style="width: 100%;"
                       :options="$users" />


        <x-form.select name="event" size="md:w-1/2 w-full" class="md:mt-0"
                       label="Event" readonly="readonly" id="event"
                       :options="$create ? [] : [$reservation->event->id => $reservation->event->start->format('d/m/Y h:i A')
                       . ' | '. $reservation->event->type->arabic_name]" />
    </x-form.group>

    <div id='calendar'></div>

    <x-button type="submit" class="mx-auto mt-2">
        <x-slot name="svg"><x-svg.edit /></x-slot>
        {{ $submit }}
    </x-button>

    <x-layouts.errors />
</div>

@push('header')
    <meta name="turbolinks-visit-control" content="reload">
@endpush

@push("scripts")

    <script defer src="{{ mix('/js/reservation.js') }}"></script>
    <link href="{{ mix('/css/reservation.css') }}" rel="stylesheet" />

@endpush
