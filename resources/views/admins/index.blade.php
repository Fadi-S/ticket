@extends("master")

@section("title")
    <title>View All admins | Ticket</title>
@endsection

@section("content")

    <div id='mainContent'>
        <div class="row gap-20 masonry pos-r">

            <div class="masonry-sizer col-md-6"></div>

            <div class="masonry-item col-md-12">
                <div class="bgc-white p-20 bd">
                    <h6 class="c-grey-900">View All Admins</h6>

                    <table class="table table-striped table-bordered dataTable">
                        <thead>
                            <tr>
                                <th>Picture</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Username</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Picture</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Username</th>
                                <th>Edit</th>
                            </tr>
                        </tfoot>

                        <tbody>
                        @foreach($admins as $admin)
                            <tr>
                                <th><img width="70" src="{{ $admin->picture }}" alt="{{ $admin->name }}'s picture"></th>
                                <td><a href="{{ url("admins/$admin->username") }}">{{ $admin->name }}</a></td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->getRoleNames()->first() }}</td>
                                <td>{{ $admin->username }}</td>
                                <td><a class="btn btn-info" href="{{ url("admins/$admin->username/edit") }}">Edit</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

@endsection
