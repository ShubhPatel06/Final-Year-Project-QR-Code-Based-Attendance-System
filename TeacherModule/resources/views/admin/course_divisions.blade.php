@extends("layouts.adminLayout")
@section('sidebar')
<x-admin-sidebar focus='course_division' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">
    <x-course-division-modal :courses='$courses' />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl ">Course Division Details</h1>

        <!-- Modal toggle -->
        <button data-modal-target="course-division-modal" data-modal-toggle="course-division-modal" class="block text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center addCourseDivision" type="button">
            Add Course Division
        </button>

    </div>
    <table class="min-w-fug-white shadow-md rounded-lg course-division-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="p-4 font-semibold text-gray-700">Division ID</th>
                <th class="p-4 font-semibold text-gray-700">Division Name</th>
                <th class="p-4 font-semibold text-gray-700">Course</th>
                <th class="p-4 font-semibold text-gray-700">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white">
        </tbody>
    </table>

</div>

<script type="text/javascript">
    $(function() {

        var table = $('.course-division-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.course_division') }}",
            columns: [{
                    data: 'division_id',
                    name: 'division_id',
                    class: "p-4"
                },
                {
                    data: 'division_name',
                    name: 'division_name',
                    class: "p-4"
                },
                {
                    data: 'course.course_code',
                    name: 'course.course_code',
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

        $('body').on('click', '.addCourseDivision', function() {
            $('#course-division-modal').removeClass('hidden');
            $('#course-division-modal').addClass('flex');
            $('#saveBtn').html("Add Course Division");
            $('#division_id').val('');
            $('#course-divisionForm').trigger("reset");
            $('#modalTitle').html("Create New Course Division");
        });

        $('#closeModal').click(function() {
            $('#course-divisionForm .error-message').remove();

            $('#course-division-modal').remove('flex');
            $('#course-division-modal').addClass('hidden');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Saving..');

            $.ajax({
                data: $('#course-divisionForm').serialize(),
                url: "{{ route('course_division.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (data.errors) {
                        // Display validation errors in the modal
                        displayErrors(data.errors);
                    } else {
                        // Reset the form and close the modal on success
                        $('#course-divisionForm .error-message').remove();

                        $('#course-divisionForm').trigger("reset");
                        $('#course-division-modal').addClass('hidden');
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

                    $('#saveBtn').html('Save Changes');
                }
            });
        });

        $('body').on('click', '.editCourseDivision', function() {
            var division_id = $(this).data('id');
            var url = "{{ url('edit-course_division') }}/" + division_id;
            $.get(url, function(data) {
                $('#modalTitle').html("Edit Course Division");
                $('#saveBtn').html("Save changes");
                $('#course-division-modal').removeClass('hidden');
                $('#course-division-modal').addClass('flex');
                $('#division_id').val(data.division_id);
                $('#division_name').val(data.division_name);
                $('#course_id').val(data.course_id);

            })
        });

        function displayErrors(errors) {
            // Remove any existing error messages
            $('#course-divisionForm .error-message').remove();

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