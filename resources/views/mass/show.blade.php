@extends("components.master")

@section("title")
    <title>View {{ $mass->formatted_date }} | Ticket</title>
@endsection

@section("content")

    <div id='mainContent'>
        <div class="row gap-20 masonry pos-r">

            <div class="masonry-sizer col-md-6"></div>

            <div class="masonry-item col-md-12">
                <div class="bgc-white p-20 bd">
                    <h6 class="c-grey-900">{{ $mass->type->arabic_name }} | {{ $mass->formatted_date }} | {{ $mass->formatted_time }}</h6>

                    <p>Places available: {{ $mass->number_of_places - $mass->reservedPlaces() }}</p>

                    <div class="row">
                        @foreach($mass->reservations as $reservation)

                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-header">
                                        Reservation of: {{ $reservation->users[0]->name }} | Number of People {{ $reservation->users()->count() }}
                                    </div>

                                    <div class="card-body">
                                        @foreach($reservation->users as $key => $user)

                                            <div class="mx-auto">
                                                <img src="{{ $user->picture }}" alt="{{ $user->name }}'s Picture" class="img-thumbnail" width="70">
                                                &nbsp;
                                                <a href="{{ url("/users/$user->username") }}">{{ $user->name }} ({{ "@".$user->username }})</a>
                                            </div>

                                            @if ($key !== array_key_last($reservation->users->toArray()))
                                                <hr>
                                            @endif

                                        @endforeach
                                    </div>

                                    <div class="card-footer">
                                        <div class="row">
                                            <a href="{{ url("/reservations/$reservation->id/edit") }}" class="btn btn-info mr-auto">Edit</a>

                                            {!! Form::open(["method" => "DELETE", "url" => url("/reservations/$reservation->id")]) !!}

                                            <a href="" class="btn btn-danger ml-auto"
                                               onclick="event.preventDefault();event.stopPropagation();
                                               if(confirm('Are you sure you want to delete this reservation?')) this.parentNode.submit();">
                                                Delete Reservation
                                            </a>

                                            {!! Form::close() !!}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>


                </div>
            </div>

        </div>
    </div>

@endsection
