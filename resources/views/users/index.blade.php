@extends("master")

@section("title")
    <title>View All users | Ticket</title>
@endsection

@section("content")

    <div id='mainContent'>
        <div class="row gap-20 masonry pos-r">

            <div class="masonry-sizer col-md-6"></div>

            <div class="masonry-item col-md-12">
                <div class="bgc-white p-20 bd">
                    <h6 class="c-grey-900">View All Users</h6>

                    <table class="table table-striped table-bordered dataTable">
                        <thead>
                            <tr>
                                <th>Picture</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Picture</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Edit</th>
                            </tr>
                        </tfoot>

                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <th><img width="70" src="{{ $user->picture }}" alt="{{ $user->name }}'s picture"></th>
                                <td><a href="{{ url("users/$user->username") }}">{{ $user->name }}</a></td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->username }}</td>
                                <td><a class="btn btn-info" href="{{ url("users/$user->username/edit") }}">Edit</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

@endsection
