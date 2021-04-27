@props(['class' => null, 'id' => null])

<div class="flex flex-col">
    <div class="overflow-x-auto my-2">
        <div class="py-2 align-middle inline-block sm:px-6 lg:px-8 min-w-full">
            <div class="shadow dark:border-gray-900 border-gray-200 sm:rounded-lg">
                <table {{ $attributes }} class="min-w-full divide-y dark:divide-gray-900
                 divide-gray-200 sm:rounded-lg {{ $class }}" id="{{ $id }}">
                    @isset($head)
                        <thead>
                        {{ $head }}
                        </thead>
                    @endisset

                    <tbody class="bg-white dark:bg-gray-800 bg-gray-50 transition-colors duration-500
                divide-y divide-gray-200 dark:divide-gray-900">

                    {{ $body }}

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
