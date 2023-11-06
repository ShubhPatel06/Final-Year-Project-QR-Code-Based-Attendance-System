@extends("layouts.teacherLayout")
@section('sidebar')
<x-teacher-sidebar focus='lecture' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl ">Lecture Details</h1>

    </div>
    <table class="min-w-fug-white shadow-md rounded-lg lectures-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="p-4 font-semibold text-gray-700">Lecture Code</th>
                <th class="p-4 font-semibold text-gray-700">Lecture Name</th>
                <th class="p-4 font-semibold text-gray-700">Course</th>
                <th class="p-4 font-semibold text-gray-700">Total Hours</th>
                <th class="p-4 font-semibold text-gray-700">Day</th>
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

        var table = $('.lectures-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('teacher.lectures') }}",
            columns: [{
                    data: 'lecture_code',
                    name: 'lecture_code',
                    class: "p-4"
                },
                {
                    data: 'lecture_name',
                    name: 'lecture_name',
                    class: "p-4"
                },
                {
                    data: 'course.course_code',
                    name: 'course.course_code',
                    class: "p-4"
                },
                {
                    data: 'total_hours',
                    name: 'total_hours',
                    class: "p-4"
                },
                {
                    data: 'day',
                    name: 'day',
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
                },
            ]
        });

    });
</script>

@endsection