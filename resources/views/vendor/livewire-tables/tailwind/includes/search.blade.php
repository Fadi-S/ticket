@if ($showSearch)
    <div class="flex rounded-md shadow-sm">
        <input
            wire:model{{ $this->searchFilterOptions }}="filters.search"
            placeholder="{{ __('Search') }}"
            type="text" dir="auto"
            class="flex-1 shadow-sm border-gray-300 block w-full
            dark:bg-gray-600 dark:placeholder-gray-400 dark:text-white dark:border-gray-500 focus:dark:border-indigo-500 transition-dark
              sm:text-sm sm:leading-5 focus:outline-none focus:border-indigo-300 focus:shadow-outline-indigo
            @if (isset($filters['search']) && strlen($filters['search'])) rounded-none ltr:rounded-l-md rtl:rounded-r-md @else rounded-md @endif"
        />

        @if (isset($filters['search']) && strlen($filters['search']))
            <span wire:click="$set('filters.search', null)"
                  class="inline-flex items-center px-3 ltr:rounded-r-md
                  rtl:rounded-l-md border border-gray-300 dark:border-gray-500
                 bg-gray-50 dark:bg-gray-600 text-gray-500 dark:text-gray-200 cursor-pointer sm:text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </span>
        @endif
    </div>
@endif
