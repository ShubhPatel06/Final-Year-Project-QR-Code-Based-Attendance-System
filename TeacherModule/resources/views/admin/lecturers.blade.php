@extends("layouts.adminLayout")
@section('sidebar')
<x-admin-sidebar focus='lecturer' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">
    <x-lecturer-modal :users='$users' :faculties='$faculties' />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl ">Lecturer Details</h1>

        <!-- Modal toggle -->
        <button data-modal-target="role-modal" data-modal-toggle="role-modal" class="block text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center addLecturer" type="button">
            Add Lecturer
        </button>

    </div>
    <table class="min-w-fug-white shadow-md rounded-lg lecturers-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="p-4 font-semibold text-gray-700">Lecturer ID</th>
                <th class="p-4 font-semibold text-gray-700">First Name</th>
                <th class="p-4 font-semibold text-gray-700">Last Name</th>
                <th class="p-4 font-semibold text-gray-700">Username</th>
                <th class="p-4 font-semibold text-gray-700">Email</th>
                <th class="p-4 font-semibold text-gray-700">Phone Number</th>
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

        var table = $('.lecturers-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.lecturers') }}",
            columns: [{
                    data: 'user_id',
                    name: 'user_id',
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
                    data: 'user.username',
                    name: 'user.username',
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
                    data: 'faculty.faculty_name',
                    name: 'faculty.faculty_name',
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

        $('body').on('click', '.addLecturer', function() {
            $('#lecturer-modal').removeClass('hidden');
            $('#onlyAdd').removeClass('hidden');
            $('#lecturer-modal').addClass('flex');
            $('#onlyEdit').addClass('hidden');
            $('#saveBtn').show();
            $('#updateBtn').hide();
            $('#saveBtn').html("Add Lecturer");
            $('#lecturer_id').val('');
            $('#lecturerForm').trigger("reset");
            $('#modalTitle').html("Create New Lecturer");
        });

        $('#closeModal').click(function() {
            $('#lecturer-modal').remove('flex');
            $('#lecturer-modal').addClass('hidden');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Saving..');

            $.ajax({
                data: $('#lecturerForm').serialize(),
                url: "{{ route('lecturer.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {

                    $('#lecturerForm').trigger("reset");
                    $('#lecturer-modal').addClass('hidden');
                    table.draw();

                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Add Lecturer');
                }
            });
        });

        $('body').on('click', '.editLecturer', function() {
            $('#onlyEdit').removeClass('hidden');
            $('#onlyAdd').addClass('hidden');
            $('#saveBtn').hide();
            $('#updateBtn').show();

            var user_id = $(this).data('id');
            var url = "{{ url('get-lecturer') }}/" + user_id;

            $.get(url, function(data) {
                $('#modalTitle').html("Edit Lecturer");
                $('#lecturer-modal').removeClass('hidden');
                $('#lecturer-modal').addClass('flex');
                $('#onlyEdit').removeClass('hidden');
                $('#onlyAdd').addClass('hidden');
                $('#user_id').val(data.user_id);
                $('#first_name').val(data.user.first_name);
                $('#last_name').val(data.user.last_name);
                $('#username').val(data.user.username);
                $('#email').val(data.user.email);
                $('#phoneNo').val(data.user.phoneNo);
                $('#editfaculty_id').val(data.faculty_id);
            })
        });

        $('#updateBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Saving..');

            $.ajax({
                data: $('#lecturerForm').serialize(),
                url: "{{ route('lecturer.edit') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {

                    $('#lecturerForm').trigger("reset");
                    $('#lecturer-modal').addClass('hidden');
                    $('#updateBtn').html('Save Changes');

                    table.draw();

                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#updateBtn').html('Save Changes');
                }
            });
        });

    });
</script>

@endsection