@extends("layouts.adminLayout")
@section('sidebar')
<x-admin-sidebar focus='course' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">
    <x-course-modal :faculties='$faculties' />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl ">Course Details</h1>

        <!-- Modal toggle -->
        <button data-modal-target="course-modal" data-modal-toggle="course-modal" class="block text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center addCourse" type="button">
            Add Course
        </button>

    </div>
    <table class="min-w-full table-auto text-leftbg-white shadow-md  courses-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="p-4 font-semibold text-gray-700">Course ID</th>
                <th class="p-4 font-semibold text-gray-700">Course Code</th>
                <th class="p-4 font-semibold text-gray-700">Course Name</th>
                <th class="p-4 font-semibold text-gray-700">Faculty</th>
                <th class="p-4 font-semibold text-gray-700">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white">
        </tbody>
    </table>

</div>

<script type="text/javascript">
    $(function() {

        var table = $('.courses-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.courses') }}",
            columns: [{
                    data: 'course_id',
                    name: 'course_id',
                    class: "p-4"
                },
                {
                    data: 'course_code',
                    name: 'course_code',
                    class: "p-4"
                },
                {
                    data: 'course_name',
                    name: 'course_name',
                    class: "p-4"
                },
                {
                    data: 'faculty_name',
                    name: 'faculty_id',
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

        $('body').on('click', '.addCourse', function() {
            $('#course-modal').removeClass('hidden');
            $('#course-modal').addClass('flex');
            $('#saveBtn').html("Add Course");
            $('#course_id').val('');
            $('#courseForm').trigger("reset");
            $('#modalTitle').html("Create New Course");
        });

        $('#closeModal').click(function() {
            $('#course-modal').remove('flex');
            $('#course-modal').addClass('hidden');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Saving..');

            $.ajax({
                data: $('#courseForm').serialize(),
                url: "{{ route('course.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {

                    $('#courseForm').trigger("reset");
                    $('#course-modal').addClass('hidden');
                    table.draw();

                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Add Course');
                }
            });
        });

        $('body').on('click', '.editCourse', function() {
            var course_id = $(this).data('id');
            var url = "{{ url('edit-course') }}/" + course_id;
            $.get(url, function(data) {
                $('#modalTitle').html("Edit Course");
                $('#saveBtn').html("Save changes");
                $('#course-modal').removeClass('hidden');
                $('#course-modal').addClass('flex');
                $('#course_id').val(data.course_id);
                $('#course_code').val(data.course_code);
                $('#course_name').val(data.course_name);
                $('#faculty_id').val(data.faculty_id);
            })
        });

    });
</script>

@endsection