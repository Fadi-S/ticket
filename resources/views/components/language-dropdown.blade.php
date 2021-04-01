@props(['class' => null])

<div x-data="{ dropdownOpen: false }" class="relative mx-2 {{ $class }}">
    <button @click="dropdownOpen = ! dropdownOpen"
            class="flex items-center justify-center space-x-2 focus:outline-none">

        <div class="relative block w-10 rounded-sm mx-1">
            <img class="h-full w-full object-cover"
                 src="{{ $locales[app()->getLocale()]['logo']  }}"
                 alt="Country's Flag">
        </div>

        <div class="text-gray-200 dark:text-white font-light text-sm mx-2 font-semibold">
            {{ $locales[app()->getLocale()]['name'] }}
        </div>
    </button>

    <div x-show="dropdownOpen" @click="dropdownOpen = false"
         class="fixed inset-0 h-full w-full z-10"
         style="display: none;"></div>

    <div x-show.transition="dropdownOpen"
         class="absolute mt-2 w-48
                              bg-white dark:bg-gray-600
                              rounded-md overflow-hidden shadow-xl z-10"
         style="display: none;">

        @foreach($locales as $key => $locale)
            <a href="{{ url("/lang/$key") }}" data-turbolinks="false"
               {{ $key === app()->getLocale() ? 'disabled' : '' }}
               class="block px-4 py-6 text-sm
                               {{ $key === app()->getLocale() ? 'font-semibold' : '' }}
                       hover:bg-gray-200 dark:hover:bg-gray-800">

                <div class="flex flex-row space-x-2">
                    <img class="w-12 object-cover mx-1"
                         src="{{ $locale['logo']  }}"
                         alt="Country's Flag">

                    <span class="mx-1">{{ $locale['name'] }}</span>

                    @if($key === app()->getLocale())
                        <x-svg.check />
                    @endif
                </div>


            </a>

        @endforeach

    </div>
</div>