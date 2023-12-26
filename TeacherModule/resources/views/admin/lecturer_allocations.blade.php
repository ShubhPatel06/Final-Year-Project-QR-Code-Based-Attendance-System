@extends("layouts.adminLayout")
@section('sidebar')
<x-admin-sidebar focus='lecturer_allocation' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">
    <x-lecturer-allocation-modal :lecturers='$lecturers' :lectures='$lectures' />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl ">Lecturer Allocation Details</h1>

        <!-- Modal toggle -->
        <button data-modal-target="lecturer_allocation-modal" data-modal-toggle="lecturer_allocation-modal" class="block text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center addLecturerAllocation" type="button">
            Add Lecturer Allocation
        </button>

    </div>
    <table class="min-w-fug-white shadow-md rounded-lg lecturer_allocation-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="p-4 font-semibold text-gray-700">Lecturer</th>
                <th class="p-4 font-semibold text-gray-700">Lecture</th>
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

        var table = $('.lecturer_allocation-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.lecturer_allocations') }}",
            columns: [{
                    data: function(row) {
                        return row.lecturer.user.first_name + ' ' + row.lecturer.user.last_name;
                    },
                    name: 'lecturer.user.full_name', // You can change the name accordingly
                    class: "p-4"
                }, {
                    data: 'lecture.lecture_name',
                    name: 'lecture.lecture_name',
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

        $('body').on('click', '.addLecturerAllocation', function() {
            $('#lecturer_allocation-modal').removeClass('hidden');
            $('#lecturer_allocation-modal').addClass('flex');
            $('#saveBtn').html("Add Lecturer Allocation");
            $('#saveBtn').show();
            $('#lecturer_id').val('');
            $('#lecture_id').val('');
            $('#group_id').val('');
            $('#lecturer_allocationForm').trigger("reset");
            $('#modalTitle').html("Create New Lecturer Allocation");
        });


        $('#lecture_id').change(function() {
            var lecture_id = $(this).val();

            $.ajax({
                url: '/get-groups/' + lecture_id,
                type: 'GET',
                success: function(data) {
                    $('#group_id').empty();

                    $('#group_id').append('<option value="" selected disabled>Select Group</option>');

                    $.each(data, function(index, group) {
                        $('#group_id').append('<option value="' + group.group.group_id + '"> ' + group.group.group_name + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Saving..');

            $.ajax({
                data: $('#lecturer_allocationForm').serialize(),
                url: "{{ route('lecturer_allocation.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (data.errors) {
                        // Display validation errors in the modal
                        displayErrors(data.errors);
                    } else {
                        // Reset the form and close the modal on success
                        $('#lecturer_allocationForm .error-message').remove();

                        $('#lecturer_allocationForm').trigger("reset");
                        $('#lecturer_allocation-modal').addClass('hidden');

                        table.draw();
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', xhr);

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Display validation errors in the modal
                        displayErrors(xhr.responseJSON.errors);
                    } else {
                        var errorMessage = "An error occurred while saving the lecturer allocation. Please try again.";

                        alert(errorMessage);
                    }

                    $('#saveBtn').html('Add Lecturer Allocation');

                }
            });
        });

        $('body').on('click', '.deleteLecturerAllocation', function() {
            var allocation_id = $(this).data("id");
            var url = "{{ url('delete-lecturer_allocation') }}/" + allocation_id;
            if (confirm("Are you sure you want to delete?")) {
                var csrfToken = "{{ csrf_token() }}";
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('lecturer_allocation.delete') }}",
                    data: {
                        "_token": csrfToken,
                        'allocation_id': allocation_id,

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
            $('#lecturer_allocationForm .error-message').remove();

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

        $('#closeModal').click(function() {
            $('#lecturer_allocationForm .error-message').remove();

            $('#lecturer_allocation-modal').remove('flex');
            $('#lecturer_allocation-modal').addClass('hidden');
        });

    });
</script>

@endsection