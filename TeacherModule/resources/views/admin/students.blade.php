@extends("layouts.adminLayout")
@section('sidebar')
<x-admin-sidebar focus='student' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">
    <x-student-modal :users='$users' :courses='$courses' />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl ">Student Details</h1>

        <!-- Modal toggle -->
        <button data-modal-target="student-modal" data-modal-toggle="student-modal" class="block text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center addStudent" type="button">
            Add Student
        </button>

    </div>
    <table class="min-w-fug-white shadow-md rounded-lg students-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="p-4 font-semibold text-gray-700">User ID</th>
                <th class="p-4 font-semibold text-gray-700">ADM No</th>
                <th class="p-4 font-semibold text-gray-700">First Name</th>
                <th class="p-4 font-semibold text-gray-700">Last Name</th>
                <th class="p-4 font-semibold text-gray-700">Email</th>
                <th class="p-4 font-semibold text-gray-700">Phone Number</th>
                <th class="p-4 font-semibold text-gray-700">Course</th>
                <th class="p-4 font-semibold text-gray-700">Year of Study</th>
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

        var table = $('.students-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.students') }}",
            columns: [{
                    data: 'user_id',
                    name: 'user_id',
                    class: "p-4"
                },
                {
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
                    data: 'action',
                    name: 'action',
                    class: "p-4",
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('body').on('click', '.addStudent', function() {
            $('#student-modal').removeClass('hidden');
            $('#student-modal').addClass('flex');
            $('#onlyAdd').removeClass('hidden');
            $('#onlyEdit').addClass('flex');
            $('#onlyEdit').removeClass('grid');
            $('#onlyEdit').addClass('hidden');
            $('#saveBtn').html("Add Student");
            $('#adm_no').val('');
            $('#studentForm').trigger("reset");
            $('#modalTitle').html("Create New Student");
        });

        $('#closeModal').click(function() {
            $('#student-modal').remove('flex');
            $('#student-modal').addClass('hidden');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Saving..');

            $.ajax({
                data: $('#studentForm').serialize(),
                url: "{{ route('student.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (data.errors) {
                        // Display validation errors in the modal
                        displayErrors(data.errors);
                    } else {
                        // Reset the form and close the modal on success
                        $('#studentForm .error-message').remove();

                        $('#studentForm').trigger("reset");
                        $('#student-modal').addClass('hidden');
                        table.draw();

                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', xhr);

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Display validation errors in the modal
                        displayErrors(xhr.responseJSON.errors);
                    } else {
                        var errorMessage = "An error occurred while saving the lecturer. Please try again.";

                        alert(errorMessage);
                    }

                    $('#saveBtn').html('Add Student');
                }
            });
        });

        $('body').on('click', '.editStudent', function() {
            var adm_no = $(this).data('adm');
            var user_id = $(this).data('id');
            var url = "{{ url('get-student') }}/" + user_id;
            $.get(url, function(data) {
                $('#student-modal').removeClass('hidden');
                $('#student-modal').addClass('flex');
                $('#onlyAdd').addClass('hidden');
                $('#onlyEdit').removeClass('hidden');
                $('#onlyEdit').addClass('grid');
                $('#updateBtn').html("Save Changes");
                $('#modalTitle').html("Edit Student");
                $('#user_id').val(data.user_id);
                $('#first_name').val(data.user.first_name);
                $('#last_name').val(data.user.last_name);
                $('#username').val(data.user.username);
                $('#email').val(data.user.email);
                $('#phoneNo').val(data.user.phoneNo);
                $('#edit_course_id').val(data.course.course_id);
                $('#edit_year_of_study').val(data.year_of_study);
                $('#edit_semester').val(data.semester);

            })
        });

        $('#updateBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Saving..');

            $.ajax({
                data: $('#studentForm').serialize(),
                url: "{{ route('student.edit') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (data.errors) {
                        // Display validation errors in the modal
                        displayErrors(data.errors);
                    } else {
                        // Reset the form and close the modal on success
                        $('#studentForm .error-message').remove();

                        $('#studentForm').trigger("reset");
                        $('#student-modal').addClass('hidden');
                        $('#updateBtn').html('Save Changes');
                        table.draw();

                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', xhr);

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Display validation errors in the modal
                        displayErrors(xhr.responseJSON.errors);
                    } else {
                        var errorMessage = "An error occurred while saving the lecturer. Please try again.";

                        alert(errorMessage);
                    }

                    $('#updateBtn').html('Save Changes');

                }
            });
        });

        function displayErrors(errors) {
            // Remove any existing error messages
            $('#studentForm .error-message').remove();

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