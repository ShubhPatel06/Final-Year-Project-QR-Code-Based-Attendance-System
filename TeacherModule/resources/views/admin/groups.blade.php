@extends("layouts.adminLayout")
@section('sidebar')
<x-admin-sidebar focus='group' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">
    <x-group-modal :divisions='$divisions' />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl ">Group Details</h1>

        <!-- Modal toggle -->
        <button data-modal-target="group-modal" data-modal-toggle="group-modal" class="block text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center addGroup" type="button">
            Add Group
        </button>

    </div>
    <table class="min-w-fug-white shadow-md rounded-lg groups-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="p-4 font-semibold text-gray-700">Group ID</th>
                <th class="p-4 font-semibold text-gray-700">Group Name</th>
                <th class="p-4 font-semibold text-gray-700">Course Division</th>
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

        var table = $('.groups-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.groups') }}",
            columns: [{
                    data: 'group_id',
                    name: 'group_id',
                    class: "p-4"
                },
                {
                    data: 'group_name',
                    name: 'group_name',
                    class: "p-4"
                },
                {
                    data: 'division.division_name',
                    name: 'division.division_name',
                    class: "p-4"
                },
                {
                    data: 'year',
                    name: 'year',
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

        $('body').on('click', '.addGroup', function() {
            $('#group-modal').removeClass('hidden');
            $('#group-modal').addClass('flex');
            $('#saveBtn').html("Add Group");
            $('#group_id').val('');
            $('#groupForm').trigger("reset");
            $('#modalTitle').html("Create New Group");
        });

        $('#closeModal').click(function() {
            $('#groupForm .error-message').remove();

            $('#group-modal').remove('flex');
            $('#group-modal').addClass('hidden');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Saving..');

            $.ajax({
                data: $('#groupForm').serialize(),
                url: "{{ route('group.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (data.errors) {
                        // Display validation errors in the modal
                        displayErrors(data.errors);
                    } else {
                        // Reset the form and close the modal on success
                        $('#groupForm .error-message').remove();

                        $('#groupForm').trigger("reset");
                        $('#group-modal').addClass('hidden');
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

                    $('#saveBtn').html('Add Group');
                }
            });
        });

        $('body').on('click', '.editGroup', function() {
            var group_id = $(this).data('id');
            var url = "{{ url('edit-group') }}/" + group_id;
            $.get(url, function(data) {
                $('#modalTitle').html("Edit Group");
                $('#saveBtn').html("Save changes");
                $('#group-modal').removeClass('hidden');
                $('#group-modal').addClass('flex');
                $('#group_id').val(data.group_id);
                $('#group_name').val(data.group_name);
                $('#division_id').val(data.division_id);
                $('#year').val(data.year);
                $('#semester').val(data.semester);
            })
        });

        function displayErrors(errors) {
            // Remove any existing error messages
            $('#groupForm .error-message').remove();

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