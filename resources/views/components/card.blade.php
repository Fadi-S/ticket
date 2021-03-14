@props(['class'=>null])

<div class="bg-white dark:bg-gray-700 transition-colors duration-500
 my-4 sm:m-4 lg:m-8 sm:rounded-lg p-4 sm:p-8 {{ $class }}" {{ $attributes }}>
        {{ $slot }}
</div>