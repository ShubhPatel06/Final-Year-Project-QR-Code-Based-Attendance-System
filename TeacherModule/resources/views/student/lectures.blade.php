@extends("layouts.studentLayout")
@section('sidebar')
<x-student-sidebar focus='lecture' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">
    <x-register-lecture-modal :courseLectures="$courseLectures" :admNo='$admNo' />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl ">Lecture Details</h1>
        <button data-modal-target="register-lecture-modal" data-modal-toggle="register-lecture-modal" class="block text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center registerLecture" type="button">
            Register for Lecture
        </button>
    </div>
    <table class="min-w-fug-white shadow-md rounded-lg lectures-table" id="lectures-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="p-4 font-semibold text-gray-700">Lecture Code</th>
                <th class="p-4 font-semibold text-gray-700">Lecture Name</th>
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

        var table = $('#lectures-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('student.lectures') }}",
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
                    data: 'group.group_name',
                    name: 'group.group_name',
                    class: "p-4",
                    render: function(data, type, row, meta) {
                        return data + ' (' + row.group.division.division_name + ')';
                    }
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

        $('body').on('click', '.registerLecture', function() {
            $('#register-lecture-modal').removeClass('hidden');
            $('#register-lecture-modal').addClass('flex');
            $('#saveBtn').html("Register");
            $('#saveBtn').show();
            $('#adm_no').val('');
            $('#group_id').val('');
            $('#register-lectureForm').trigger("reset");
            $('#modalTitle').html("Register for Lecture");
            var admNoValue = $('#adm_no').val();
            console.log('adm_no value:', admNoValue);
        });

        $('#closeModal').click(function() {
            $('#register-lectureForm .error-message').remove();

            $('#register-lecture-modal').remove('flex');
            $('#register-lecture-modal').addClass('hidden');
        });

        $('#lecture_id').change(function() {
            var lecture_id = $(this).val();

            $.ajax({
                url: '/get-groups-by-lecture/' + lecture_id,
                type: 'GET',
                success: function(data) {
                    $('#group_id').empty();

                    $('#group_id').append('<option value="" selected disabled>Select Group</option>');

                    $.each(data, function(index, group) {
                        $('#group_id').append('<option value="' + group.group.group_id + '"> ' + group.group.group_name + ' (' + group.group.division.division_name + ')</option>');

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
                data: $('#register-lectureForm').serialize(),
                url: "{{ route('student.registerLecture') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (data.errors) {
                        // Display validation errors in the modal
                        displayErrors(data.errors);
                    } else {
                        // Reset the form and close the modal on success
                        $('#register-lectureForm .error-message').remove();

                        $('#register-lectureForm').trigger("reset");
                        $('#register-lecture-modal').addClass('hidden');

                        table.draw();
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', xhr);

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Display validation errors in the modal
                        displayErrors(xhr.responseJSON.errors);
                    } else {
                        var errorMessage = "An error occurred while registering the lecture. Please try again.";

                        alert(errorMessage);
                    }

                    $('#saveBtn').html('Register');

                }
            });
        });

        function displayErrors(errors) {
            // Remove any existing error messages
            $('#register-lectureForm .error-message').remove();

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