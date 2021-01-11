<x-master>
    <x-slot name="title">
        View {{ $user->name }} | Ticket
    </x-slot>

    <livewire:users.user-form :user="$user" />

    <livewire:change-password-form />

</x-master>