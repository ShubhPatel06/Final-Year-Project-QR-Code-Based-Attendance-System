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
    <table class="min-w-fug-white shadow-md rounded-lg lectures-table" id="attendance-table">
        <thead>
            <tr class="bg-slate-200">

                <th class="p-4 font-semibold text-gray-700">Date</th>
                <th class="p-4 font-semibold text-gray-700">Start Time</th>
                <th class="p-4 font-semibold text-gray-700">End Time</th>
                <th class="p-4 font-semibold text-gray-700">Action</th>
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
                    data: 'action',
                    name: 'action',
                    class: "p-4",
                    orderable: false,
                    searchable: false
                }
            ]
        });

    });
</script>

@endsection