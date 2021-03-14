@props(['class' => null, 'id' => null])

<div class="flex flex-col">
    <div class="overflow-x-auto ">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden border-b
            dark:border-gray-900
             border-gray-200 sm:rounded-lg">
            <table {{ $attributes }} class="min-w-full divide-y
            dark:divide-gray-900
            divide-gray-200 {{ $class }}" id="{{ $id }}">
                @isset($head)
                    <thead>
                        {{ $head }}
                    </thead>
                @endisset

                <tbody class="bg-white dark:bg-gray-800 transition-colors duration-500
                divide-y divide-gray-200 dark:divide-gray-900">

                {{ $body }}

                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>