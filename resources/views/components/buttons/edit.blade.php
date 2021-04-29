@props([
    'url',
])

<div class="flex items-center justify-start">
    <a class="border-2 border-blue-500 dark:border-blue-800 transition-dark
                                dark:hover:bg-blue-700 flex hover:bg-blue-300
                                 items-center justify-center p-2 rounded-full text-md text-white"
       href="{{ $url }}">

        <x-svg.edit class="text-blue-500" />
    </a>
</div>
