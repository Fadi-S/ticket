<table dir="rtl">
    <tr>
        <th>الميعاد</th>
        <th>التاريخ</th>
        <th>مناسبة الحضور</th>
    </tr>
    <tr style="text-align: center;">
        <td dir="ltr">{{ $event->start->translatedFormat('h:i a') }}</td>
        <td dir="ltr">{{ $event->start->translatedFormat('l, j F') }}</td>
        <td>{{ $event->description }}</td>
    </tr>
</table>

@php($names = [
    'deacons' => 'الشمامسة',
    'males' => 'الرجال',
    'females' => 'السيدات',
])

@foreach($usersGroup as $gender => $users)
    <table>
        <thead>
        <tr>
            <th style="background-color: #0a0302"></th>
            <th style="background-color: #0a0302"></th>
            <th style="background-color: #0a0302; color: white; font-weight: bold;">{{ $names[$gender] }}</th>
        </tr>
        </thead>
        <tbody>
        @php($i = 1)
        @foreach($users as $user)
            <tr>
                <td>{{ $i }}</td>
                <td>#{{ $user->id }}</td>
                <td>{{ $user->arabic_name }}</td>
            </tr>

            @php($i++)
        @endforeach
        </tbody>
    </table>
@endforeach
