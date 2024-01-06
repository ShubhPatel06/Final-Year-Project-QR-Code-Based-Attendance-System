@extends("layouts.teacherLayout")
@section('sidebar')
<x-teacher-sidebar focus='lecture_group' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl ">Lectures and Groups Details</h1>

    </div>

    <table class="min-w-fug-white shadow-md rounded-lg groups-table" id="groups-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="p-4 font-semibold text-gray-700">Lecture Code</th>
                <th class="p-4 font-semibold text-gray-700">Lecture</th>
                <th class="p-4 font-semibold text-gray-700">Course</th>
                <th class="p-4 font-semibold text-gray-700">Group</th>
                <th class="p-4 font-semibold text-gray-700">Year</th>
                <th class="p-4 font-semibold text-gray-700">Semester</th>
                <th class="p-4 font-semibold text-gray-700">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white">
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(function() {

        var groupsTable = $('.groups-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('teacher.lecture_groups') }}",
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
                    data: 'group.group_name',
                    name: 'group.group_name',
                    class: "p-4"
                },
                {
                    data: 'group.year',
                    name: 'group.year',
                    class: "p-4"
                },
                {
                    data: 'group.semester',
                    name: 'group.semester',
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