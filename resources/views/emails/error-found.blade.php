There was a {{ $type }} entry recorded at {{ $time->format('Y-m-d h:i a') }} in the url {{ $url }}
by <div>{{ $user ? $user->arabic_name : '-' }}</div>
<a href="{{ $view_in_Telescope }}">View</a>
