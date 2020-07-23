@extends("master")

@section("title")
    <title>View All Masses | Ticket</title>
@endsection

@section("content")

    <div id='mainContent'>
        <div class="row gap-20 masonry pos-r">

            <div class="masonry-sizer col-md-6"></div>

            <div class="masonry-item col-md-12">
                <div class="bgc-white p-20 bd">
                    <h6 class="c-grey-900">View All Masses</h6>

                    <table class="table table-striped table-bordered dataTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Description</th>
                                <th>Number of places</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Description</th>
                                <th>Number of places</th>
                                <th>Edit</th>
                            </tr>
                        </tfoot>

                        <tbody>
                        @foreach($masses as $mass)
                            <tr>
                                <td><a href="{{ url("masses/$mass->id") }}">{{ $mass->formatted_date }}</a></td>
                                <td>{{ $mass->start->format("H:i A") }} - {{ $mass->end->format("H:i A") }}</td>
                                <td>{{ $mass->description }}</td>
                                <td>{{ $mass->number_of_places }}</td>
                                <td><a class="btn btn-info" href="{{ url("masses/$mass->id/edit") }}">Edit</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

@endsection
