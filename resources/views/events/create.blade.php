<x-master>
    <x-slot name="title">Create Event | Ticket</x-slot>

    <x-card>
        {!! Form::open(["url" => url($url), "method" => "POST"]) !!}

        @include("events.form", ["create" => true, "submit" => __('Create Event')])

        {!! Form::close() !!}
    </x-card>
</x-master>
