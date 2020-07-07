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
    @foreach (session('flash_notification', collect())->toArray() as $message)
        @if ($message['overlay'])
            @include('flash::modal', [
                'modalClass' => 'flash-modal',
                'title'      => $message['title'],
                'body'       => $message['message']
            ])
        @else
            <div id="alert_flash" class="alert alert-{{ $message['level'] }} {{ $message['important'] ? 'alert-important' : '' }}" role="alert">
                @if ($message['important'])
                    <button type="button" class="close" data-dismiss="alert" id="closeIMP" aria-hidden="true">&nbsp;&times;</button>
                    <script>
                        document.getElementById("closeIMP").addEventListener("click", function() {
                            document.getElementById("alert_flash").classList.add('fadeOut');
                        });
                    </script>
                @endif
                {!! $message['message'] !!}
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