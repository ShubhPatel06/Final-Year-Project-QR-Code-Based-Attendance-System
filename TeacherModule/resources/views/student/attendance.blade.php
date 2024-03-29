@extends("layouts.studentLayout")
@section('sidebar')
<x-student-sidebar focus='lecture' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">

    <div class=" mb-6">
        <h1 class="text-3xl ">{{$lecture->lecture_name}} {{$group->group_name}} ({{$group->division->division_name}})</h1>
        <h2 class="text-2xl mt-4">Attendance Details</h2>
    </div>
    <div class="mb-6">
        <p class="text-xl mb-1">Hours Present: {{ $presentHours }}</p>
        <p class="text-xl mb-1">Hours Absent: {{ $absentHours }}</p>
        <p class="text-xl">Absent Percentage: {{ $absentPercent }}%</p>
    </div>
    <table class="min-w-fug-white shadow-md rounded-lg lectures-table" id="attendance-table">
        <thead>
            <tr class="bg-slate-200">

                <th class="p-4 font-semibold text-gray-700">Date</th>
                <th class="p-4 font-semibold text-gray-700">Start Time</th>
                <th class="p-4 font-semibold text-gray-700">End Time</th>
                <th class="p-4 font-semibold text-gray-700">Hours</th>
                <th class="p-4 font-semibold text-gray-700">Attendance</th>
            </tr>
        </thead>
        <tbody class="bg-white">
        </tbody>
    </table>

</div>
<script type="text/javascript">
    $(function() {
        function getURLParameter(index) {
            var pathSegments = window.location.pathname.split('/');
            return pathSegments[index];
        }

        // Get values from the URL
        var lectureID = getURLParameter(2);
        var groupID = getURLParameter(3);

        var table = $('#attendance-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('student-attendance') }}/" + lectureID + "/" + groupID,
            columns: [{
                    data: 'date',
                    name: 'date',
                    class: "p-4"
                },
                {
                    data: 'start_time',
                    name: 'start_time',
                    class: "p-4"
                },
                {
                    data: 'end_time',
                    name: 'end_time',
                    class: "p-4"
                },
                {
                    data: 'hours',
                    name: 'hours',
                    class: "p-4"
                },
                {
                    data: 'is_present',
                    name: 'is_present',
                    class: "p-4",
                    render: function(data, type, row) {
                        if (data === null) {
                            return '<span class="bg-amber-400 p-[0.35rem] rounded-md text-white font-semibold">Not Marked</span>';
                        } else if (data === 1) {
                            return '<span class="bg-emerald-400 p-[0.35rem] rounded-md text-white font-semibold">Present</span>';
                        } else if (data === 2) {
                            return '<span class="bg-red-400 p-[0.35rem] rounded-md text-white font-semibold">Absent</span>';
                        } else {
                            return '';
                        }
                    }
                },

            ]
        });

    });
</script>

@endsection