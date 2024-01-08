@extends("layouts.adminLayout")
@section('sidebar')
<x-admin-sidebar focus='attendance' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">

    <div class=" mb-6">
        <h1 class="text-3xl ">{{$data->lecture->lecture_name}} {{$data->group->group_name}} ({{$data->group->division->division_name}})</h1>
        <h2 class="text-2xl mt-4">Student Attendance Details</h2>
    </div>

    <table class="min-w-fug-white shadow-md rounded-lg students-table" id="students-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="p-4 font-semibold text-gray-700">Admission Number</th>
                <th class="p-4 font-semibold text-gray-700">First Name</th>
                <th class="p-4 font-semibold text-gray-700">Last Name</th>
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
        function getRecordIdFromUrl() {

            var url = window.location.href;
            var parts = url.split('/');
            return parts[parts.length - 1];
        }

        var recordID = getRecordIdFromUrl();

        var table = $('.students-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('admin-studentRecords') }}/" + recordID,
            columns: [{
                    data: 'student_adm_no',
                    name: 'student_adm_no',
                    class: "p-4"
                },
                {
                    data: 'student.user.first_name',
                    name: 'student.user.first_name',
                    class: "p-4"
                },
                {
                    data: 'student.user.last_name',
                    name: 'student.user.last_name',
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