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
            </tr>
        </thead>
        <tbody class="bg-white">
        </tbody>
    </table>
</div>

<script type="text/javascript">
    function getGroupIdFromUrl() {
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
                    data: 'student.user.email',
                    name: 'student.user.email',
                    class: "p-4"
                },
                {
                    data: 'student.user.phoneNo',
                    name: 'student.user.phoneNo',
                    class: "p-4"
                },
                {
                    data: 'student.course.course_code',
                    name: 'student.course.course_code',
                    class: "p-4"
                },
                {
                    data: 'student.year_of_study',
                    name: 'student.year_of_study',
                    class: "p-4"
                },
                {
                    data: 'student.semester',
                    name: 'student.semester',
                    class: "p-4"
                },

            ]
        });

    });
</script>

@endsection