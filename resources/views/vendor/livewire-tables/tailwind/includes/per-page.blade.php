@if ($paginationEnabled && $showPerPage)
    <div class="w-full md:w-auto">
        <select
            wire:model="perPage"
            id="perPage"
            class="rounded-md shadow-sm block w-full pl-3 pr-10 py-2
             text-base leading-6 border-gray-300 focus:outline-none transition-dark
             dark:bg-gray-600 dark:focus:border-gray-800 dark:border-gray-500
              focus:border-indigo-300 focus:shadow-outline-indigo sm:text-sm sm:leading-5"
        >
            @foreach ($perPageAccepted as $item)
                <option value="{{ $item }}">{{ $item === -1 ? __('All') : $item }}</option>
            @endforeach
        </select>
    </div>
@endif
