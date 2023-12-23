@extends("layouts.adminLayout")
@section('sidebar')
<x-admin-sidebar focus='lecture' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">
    <x-lecture-modal :courses='$courses' :lecturers='$lecturers' />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl ">Lecture Details</h1>

        <!-- Modal toggle -->
        <button data-modal-target="lecture-modal" data-modal-toggle="lecture-modal" class="block text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center addLecture" type="button">
            Add Lecture
        </button>

    </div>
    <table class="min-w-fug-white shadow-md rounded-lg lectures-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="p-4 font-semibold text-gray-700">Lecture ID</th>
                <th class="p-4 font-semibold text-gray-700">Lecture Code</th>
                <th class="p-4 font-semibold text-gray-700">Lecture Name</th>
                <th class="p-4 font-semibold text-gray-700">Course</th>
                <th class="p-4 font-semibold text-gray-700">Lecturer</th>
                <th class="p-4 font-semibold text-gray-700">Total Hours</th>
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
            ajax: "{{ route('admin.lectures') }}",
            columns: [{
                    data: 'lecture_id',
                    name: 'lecture_id',
                    class: "p-4"
                },
                {
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
                    data: 'lecturer.user.first_name',
                    name: 'lecturer.user.first_name',
                    class: "p-4",
                    render: function(data, type, row) {
                        // Combine first_name and last_name
                        return row.lecturer.user.first_name + ' ' + row.lecturer.user.last_name;
                    }
                },
                {
                    data: 'total_hours',
                    name: 'total_hours',
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

        $('body').on('click', '.addLecture', function() {
            $('#lecture-modal').removeClass('hidden');
            $('#lecture-modal').addClass('flex');
            $('#saveBtn').html("Add Lecture");
            $('#lecture_id').val('');
            $('#lectureForm').trigger("reset");
            $('#modalTitle').html("Create New Lecture");
        });

        $('#closeModal').click(function() {
            $('#lectureForm .error-message').remove();

            $('#lecture-modal').remove('flex');
            $('#lecture-modal').addClass('hidden');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Saving..');

            $.ajax({
                data: $('#lectureForm').serialize(),
                url: "{{ route('lecture.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (data.errors) {
                        // Display validation errors in the modal
                        displayErrors(data.errors);
                    } else {
                        // Reset the form and close the modal on success
                        $('#lectureForm .error-message').remove();

                        $('#lectureForm').trigger("reset");
                        $('#lecture-modal').addClass('hidden');
                        table.draw();
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', xhr);

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Display validation errors in the modal
                        displayErrors(xhr.responseJSON.errors);
                    } else {
                        var errorMessage = "An error occurred while saving the lecture. Please try again.";

                        alert(errorMessage);
                    }

                    $('#saveBtn').html('Add Lecture');
                }
            });
        });

        $('body').on('click', '.editLecture', function() {

            var lecture_id = $(this).data('id');
            var url = "{{ url('edit-lecture') }}/" + lecture_id;

            $.get(url, function(data) {
                $('#modalTitle').html("Edit Lecture");
                $('#saveBtn').html("Save changes");
                $('#lecture-modal').removeClass('hidden');
                $('#lecture-modal').addClass('flex');
                $('#lecture_id').val(data.lecture_id);
                $('#lecture_code').val(data.lecture_code);
                $('#lecture_name').val(data.lecture_name);
                $('#total_hours').val(data.total_hours);
                $('#course_id').val(data.course.course_id);
                $('#lecturer_id').val(data.lecturer.user.user_id);
            })
        });

        function displayErrors(errors) {
            // Remove any existing error messages
            $('#lectureForm .error-message').remove();

            // Display validation errors in the modal
            $.each(errors, function(field, messages) {
                var fieldInput = $('#' + field);
                var errorMessage = '<div class="error-message text-red-500 text-sm mt-1">' + messages.join('<br>') + '</div>';
                fieldInput.after(errorMessage);
            });

            var firstErrorField = Object.keys(errors)[0];
            if (firstErrorField) {
                $('#' + firstErrorField).focus();
            }
        }
    });
</script>

@endsection