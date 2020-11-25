<div class="space-y-6">

    <x-form.group>
        <x-form.input type="text" size="w-1/2" name="name" id="name" label="Name"
                      :value="!$create ? $admin->name : null" placeholder="Name" />

        <x-form.input type="text" size="w-1/2" name="username" id="username" label="Username"
                      :value="!$create ? $admin->username : null" placeholder="Username" />
        <small id="message"></small>
    </x-form.group>

    <x-form.group>
        <x-form.input type="email" size="w-1/2" name="email" id="email" label="Email"
                      :value="!$create ? $admin->email : null" placeholder="Email" />

        <x-form.input type="password" size="w-1/2" name="password" id="password" label="Password" placeholder="Password" />
    </x-form.group>

    <x-form.group>

        <x-form.select name="role_id" id="role_id" size="w-1/2" label="Admin Role"
                       :options="$roles" :checked="!$create ? $admin->roles[0]->id : null" />

    </x-form.group>

    <x-button type="submit" class="mx-auto mt-2">
        <x-slot name="svg">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
            </svg>
        </x-slot>

        {{ $submit }}
    </x-button>
</div>

<input type="hidden" id="userId" value="{{ $create ? "0" : $admin->id }}">
<input type="hidden" id="url" value="{{ url("/") }}">

@push("scripts")

    {!! Html::script("js/username-check.js") !!}

@endpush

@include("errors.list")
