@props(['class' => null, 'size' => 'w-full sm:w-9/12 md:w-1/2 lg:w-1/4'])

<div class="bg-white p-4 border {{ $size }} mx-auto {{ $class }}
        rounded-lg shadow-md flex space-x-4 items-center justify-center">
    <svg class="w-6 h-6 text-red-800" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
              clip-rule="evenodd"></path>
    </svg>

    <span class="text-gray-700 text-md font-medium">{{ $slot }}</span>
</div>