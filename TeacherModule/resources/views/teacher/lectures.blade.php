@extends("layouts.teacherLayout")
@section('sidebar')
<x-teacher-sidebar focus='lecture' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl ">Lecture Details</h1>

    </div>
    <table class="min-w-fug-white shadow-md rounded-lg lectures-table" id="lectures-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="p-4 font-semibold text-gray-700">Lecture Code</th>
                <th class="p-4 font-semibold text-gray-700">Lecture Name</th>
                <th class="p-4 font-semibold text-gray-700">Course</th>
                <th class="p-4 font-semibold text-gray-700">Total Hours</th>
            </tr>
        </thead>
        <tbody class="bg-white">
        </tbody>
    </table>

</div>

<script type="text/javascript">
    $(function() {

        var table = $('#lectures-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('teacher.lectures') }}",
            columns: [{
                    data: 'lecture.lecture_code',
                    name: 'lecture.lecture_code',
                    class: "p-4"
                },
                {
                    data: 'lecture.lecture_name',
                    name: 'lecture.lecture_name',
                    class: "p-4"
                },
                {
                    data: 'lecture.course.course_code',
                    name: 'lecture.course.course_code',
                    class: "p-4"
                },
                {
                    data: 'lecture.total_hours',
                    name: 'lecture.total_hours',
                    class: "p-4"
                },
            ]
        });
    });
</script>

@endsection