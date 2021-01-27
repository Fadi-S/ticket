@props(['class' => null, 'id' => null])

<div class="flex flex-col">
        <div class="align-middle inline-block">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 {{ $class }}" id="{{ $id }}">
                    <thead>
                        {{ $head }}
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">

                    {{ $body }}

                    </tbody>
                </table>
            </div>
        </div>
</div>