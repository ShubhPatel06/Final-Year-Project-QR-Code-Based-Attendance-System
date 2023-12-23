@extends("layouts.adminLayout")
@section('sidebar')
<x-admin-sidebar focus='student_group' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">
    <x-student-group-modal :students='$students' :groups='$groups' />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl ">Student Groups Details</h1>

        <!-- Modal toggle -->
        <button data-modal-target="student_group-modal" data-modal-toggle="student_group-modal" class="block text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center addStudentGroup" type="button">
            Add Student to Group
        </button>

    </div>
    <table class="min-w-fug-white shadow-md rounded-lg student_groups-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="p-4 font-semibold text-gray-700">Adm No</th>
                <th class="p-4 font-semibold text-gray-700">First Name</th>
                <th class="p-4 font-semibold text-gray-700">Last Name</th>
                <th class="p-4 font-semibold text-gray-700">Group</th>
                <th class="p-4 font-semibold text-gray-700">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white">
        </tbody>
    </table>

</div>

<script type="text/javascript">
    $(function() {

        var table = $('.student_groups-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.student_groups') }}",
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
                    data: 'group.group_name',
                    name: 'group.group_name',
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

        $('body').on('click', '.addStudentGroup', function() {
            $('#student_group-modal').removeClass('hidden');
            $('#student_group-modal').addClass('flex');
            $('#saveBtn').html("Add Student to Group");
            $('#saveBtn').show();
            $('#adm_no').val('');
            $('#group_id').val('');
            $('#student_groupForm').trigger("reset");
            $('#modalTitle').html("Add Student to Group");
        });

        $('#closeModal').click(function() {
            $('#student_groupForm .error-message').remove();

            $('#student_group-modal').remove('flex');
            $('#student_group-modal').addClass('hidden');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Saving..');

            $.ajax({
                data: $('#student_groupForm').serialize(),
                url: "{{ route('student_group.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (data.errors) {
                        // Display validation errors in the modal
                        displayErrors(data.errors);
                    } else {
                        // Reset the form and close the modal on success
                        $('#student_groupForm .error-message').remove();

                        $('#student_groupForm').trigger("reset");
                        $('#student_group-modal').addClass('hidden');

                        table.draw();
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', xhr);

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Display validation errors in the modal
                        displayErrors(xhr.responseJSON.errors);
                    } else {
                        var errorMessage = "An error occurred while saving the user. Please try again.";

                        alert(errorMessage);
                    }

                    $('#saveBtn').html('Add Student to Group');

                }
            });
        });

        $('body').on('click', '.deleteStudentGroup', function() {
            var group_id = $(this).data("id");
            var adm_no = $(this).data("del");
            var url = "{{ url('delete-student_group') }}/" + adm_no;
            if (confirm("Are you sure you want to delete?")) {
                var csrfToken = "{{ csrf_token() }}";
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('student_group.delete') }}",
                    data: {
                        "_token": csrfToken,
                        'group_id': group_id,
                        'adm_no': adm_no,
                    },
                    success: function(data) {
                        table.draw();
                    },
                    error: function(data) {
                        alert('Error:', data);
                    }
                });
            }
        });

        function displayErrors(errors) {
            // Remove any existing error messages
            $('#student_groupForm .error-message').remove();

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