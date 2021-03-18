<x-master>
    <x-slot name="title">
        View {{ $user->name }} | Ticket
    </x-slot>

    @if($user->hasRole('agent'))
        @can('viewAgentDetails')
            <x-card>
                <div class="text-center text-2xl font-bold">
                    {{ __('Table of reservations') }}
                </div>
                <x-agent-status :agent="$user" empty-message="{{ __('This account made no reservations') }}" />
            </x-card>
        @endcan
    @endif

    <livewire:users.user-form :user="$user" />

    @if($user->isSignedIn())
        <livewire:change-password-form />
    @endif
</x-master>