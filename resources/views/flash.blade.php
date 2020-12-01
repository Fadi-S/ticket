<style>
    .alert {
        -webkit-border-radius:8px;
        -moz-border-radius:8px;
        border-radius: 8px;
        position: relative;
        margin: 10px 0;
    }
</style>
<div style="display: block; position: fixed; right: 15px; bottom: 15px; z-index: 10000;">

    @php
    $colors = [
        'success' => 'bg-green-200 text-green-800 border-green-600',
        'warning' => 'bg-yellow-200 text-yellow-800 border-yellow-600',
        'info' => 'bg-blue-200 text-blue-800 border-blue-600',
        'danger' => 'bg-red-200 text-red-800 border-red-600'
    ];
    @endphp

    @foreach (session('flash_notification', collect())->toArray() as $message)
        @if ($message['overlay'])
            @include('flash::modal', [
                'modalClass' => 'flash-modal',
                'title'      => $message['title'],
                'body'       => $message['message']
            ])
        @else
            @php($color = $colors[$message['level']])

            <div id="alert_flash" class="alert py-2 px-4
             {{ $color }}
              border {{ $message['important'] ? 'alert-important' : '' }}"
                 role="alert">

                {!! $message['message'] !!}

                @if ($message['important'])
                    <button type="button" class="close" data-dismiss="alert" id="closeIMP" aria-hidden="true">&times;</button>
                    <script>
                        document.getElementById("closeIMP").addEventListener("click", function() {
                            document.getElementById("alert_flash").classList.add('fadeOut');
                        });
                    </script>
                @endif
            </div>
        @endif
        <script>
            $('div.alert').not(".alert-important, .alert-normal").each(function(i){
                $(this).delay(5000 * (i+1)).hide(500);
            });
            $('#flash-overlay-modal').modal();
        </script>
    @endforeach
</div>
{{ session()->forget('flash_notification') }}