@extends("master")

@section("title")
    <title>Activity Logs | Ticket</title>
@endsection

@section("content")

    <div id='mainContent'>
        <div class="row gap-20 masonry pos-r">

            <div class="masonry-sizer col-md-6"></div>

            <div class="masonry-item col-md-12">
                <div class="bgc-white p-20 bd">
                    <h6 class="c-grey-900">Activity Log</h6>

                    <table class="table table-striped table-bordered dataTable">
                        <thead>
                        <tr>
                            <th>Picture</th>
                            <th>Action</th>
                            <th>Changes</th>
                            <th>Restore</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Picture</th>
                            <th>Action</th>
                            <th>Changes</th>
                            <th>Restore</th>
                        </tr>
                        </tfoot>

                        <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td><img width="70" src="{{ ($log->causer) ? $log->causer->picture : "https://bostonglobe-prod.cdn.arcpublishing.com/resizer/EdOe8MZVreAeCmuV2k6btg-LTKA=/420x0/arc-anglerfish-arc2-prod-bostonglobe.s3.amazonaws.com/public/HCIJCIQ2UAI6VKP2L3WHYVGSUE.jpg" }}" alt="{{ ($log->causer) ? $log->causer->name : "System" }}'s picture"></td>
                                <td>
                                    <strong>{{ ($log->causer) ? $log->causer->name : "System" }}</strong>
                                    {{ $log->description }}
                                    <strong>{{ $log->subject["name"] ?? $log->subject["start"] }}</strong>
                                    {{ $log->created_at->diffforhumans() }}
                                </td>
                                <td>
                                    @if($log->description == 'updated')
                                        <button class="btn btn-success" data-target="#changes{{ $log->id }}" data-toggle="modal">View Changes</button>
                                    @endif
                                </td>
                                <td>
                                    @if($log->description == 'deleted')
                                        <a class="btn btn-danger" href="{{ url("/logs/$log->id/restore") }}">Restore</a>
                                    @endif
                                </td>
                            </tr>
                            @if($log->description == 'updated')
                                <div class="modal fade" id="changes{{ $log->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <span style="font-weight: bold;">Changes</span>
                                                <button type="button" class="close"
                                                        data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <span>Old: <pre>{{ print_r($log->changes["old"], true) }}</pre></span>
                                                <br>
                                                <span>New: <pre>{{ print_r($log->changes["attributes"], true) }}</pre></span>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-info" data-dismiss="modal">Ok</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

@endsection
