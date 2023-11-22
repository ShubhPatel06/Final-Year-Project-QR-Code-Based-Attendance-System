@extends("layouts.teacherLayout")
@section('sidebar')
<x-teacher-sidebar focus='attendance' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl ">Student Attendance Details</h1>
    </div>

    <table class="min-w-fug-white shadow-md rounded-lg students-table" id="students-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="p-4 font-semibold text-gray-700">Admission Number</th>
                <th class="p-4 font-semibold text-gray-700">First Name</th>
                <th class="p-4 font-semibold text-gray-700">Last Name</th>
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

        $(function() {
            var recordID = getRecordIdFromUrl();

            var table = $('.students-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('teacher-studentRecords') }}/" + recordID,
                columns: [{
                        data: 'adm_no',
                        name: 'adm_no',
                        class: "p-4"
                    },
                    {
                        data: 'user.first_name',
                        name: 'user.first_name',
                        class: "p-4"
                    },
                    {
                        data: 'user.last_name',
                        name: 'user.last_name',
                        class: "p-4"
                    },
                    {
                        data: 'is_present',
                        name: 'is_present',
                        class: "p-4"
                    },

                ]
            });

        });
    });
</script>

@endsection