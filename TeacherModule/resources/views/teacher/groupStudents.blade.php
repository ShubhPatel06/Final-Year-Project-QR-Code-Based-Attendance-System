@extends("layouts.teacherLayout")
@section('sidebar')
<x-teacher-sidebar focus='' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl ">Group {{ $groupName }} - Student Details</h1>
    </div>

    <table class="min-w-fug-white shadow-md rounded-lg students-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="p-4 font-semibold text-gray-700">ADM No</th>
                <th class="p-4 font-semibold text-gray-700">First Name</th>
                <th class="p-4 font-semibold text-gray-700">Last Name</th>
                <th class="p-4 font-semibold text-gray-700">Email</th>
                <th class="p-4 font-semibold text-gray-700">Phone Number</th>
                <th class="p-4 font-semibold text-gray-700">Course</th>
                <th class="p-4 font-semibold text-gray-700">Year of Study</th>
                <th class="p-4 font-semibold text-gray-700">Semester</th>
                <th class="p-4 font-semibold text-gray-700">Group</th>
            </tr>
        </thead>
        <tbody class="bg-white">
        </tbody>
    </table>
</div>

<script type="text/javascript">
    function getGroupIdFromUrl() {
        // Example URL: http://127.0.0.1:8000/teacher-group-students/1
        var url = window.location.href;
        var parts = url.split('/');
        return parts[parts.length - 1];
    }

    $(function() {
        var groupID = getGroupIdFromUrl();

        var table = $('.students-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('teacher-groupStudents') }}/" + groupID,
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
                    data: 'user.email',
                    name: 'user.email',
                    class: "p-4"
                },
                {
                    data: 'user.phoneNo',
                    name: 'user.phoneNo',
                    class: "p-4"
                },
                {
                    data: 'course.course_code',
                    name: 'course.course_code',
                    class: "p-4"
                },
                {
                    data: 'year_of_study',
                    name: 'year_of_study',
                    class: "p-4"
                },
                {
                    data: 'semester',
                    name: 'semester',
                    class: "p-4"
                },
                {
                    data: 'group.group_name',
                    name: 'group.group_name',
                    class: "p-4"
                },
            ]
        });

    });
</script>

@endsection