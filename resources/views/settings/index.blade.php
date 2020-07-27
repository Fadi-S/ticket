@extends("master")

@section("title")
    <title>View All Settings | Ticket</title>
@endsection

@section("content")

    <div id='mainContent'>
        <div class="row gap-20 masonry pos-r">

            <div class="masonry-sizer col-md-6"></div>

            <div class="masonry-item col-md-12">
                <div class="bgc-white p-20 bd">
                    <h6 class="c-grey-900">View All Settings</h6>

                    <table class="table table-striped table-bordered dataTable">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Value</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Value</th>
                                <th>Edit</th>
                            </tr>
                        </tfoot>

                        <tbody>
                        @foreach($settings as $setting)
                            <tr>
                                <td></td>
                                <td>{{ $setting->name }}</td>
                                <td>{{ $setting->value }}</td>
                                <td><a class="btn btn-info" href="{{ url("settings/$setting->id/edit") }}">Edit</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

@endsection
