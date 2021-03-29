@props(['class' => null, 'size' => ''])

<div class="bg-white border dark:bg-gray-800 dark:border-gray-500 flex items-center transition-colors duration-500
 justify-center mx-auto p-4 rounded-lg shadow-md space-x-4 {{ $size }} {{ $class }}">
    <svg class="dark:text-green-400 h-6 text-green-800 w-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000
                                  16zm3.707-9.293a1 1 0 00-1.414-1.414L9
                                   10.586 7.707 9.293a1 1 0 00-1.414 1.414l2
                                    2a1 1 0 001.414 0l4-4z"
              clip-rule="evenodd"></path>
    </svg>

    <span class="dark:text-white font-medium text-gray-700 text-md">{{ $slot }}</span>
</div>