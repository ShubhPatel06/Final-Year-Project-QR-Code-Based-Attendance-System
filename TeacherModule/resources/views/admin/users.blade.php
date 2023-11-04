@extends("layouts.adminLayout")
@section('sidebar')
<x-admin-sidebar focus='users' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">
    <x-user-modal :roles='$roles' />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl ">Users Details</h1>

        <!-- Modal toggle -->
        <button data-modal-target="user-modal" data-modal-toggle="user-modal" class="block text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center addUser" type="button">
            Add User
        </button>

    </div>
    <table class="min-w-fug-white shadow-md rounded-lg users-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="p-4 font-semibold text-gray-700">User ID</th>
                <th class="p-4 font-semibold text-gray-700">First Name</th>
                <th class="p-4 font-semibold text-gray-700">Last Name</th>
                <th class="p-4 font-semibold text-gray-700">Username</th>
                <th class="p-4 font-semibold text-gray-700">Email</th>
                <th class="p-4 font-semibold text-gray-700">Phone Number</th>
                <th class="p-4 font-semibold text-gray-700">Role</th>
                <th class="p-4 font-semibold text-gray-700">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white">
        </tbody>
    </table>

</div>

<script type="text/javascript">
    $(function() {

        var table = $('.users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.users') }}",
            columns: [{
                    data: 'user_id',
                    name: 'user_id',
                    class: "p-4"
                },
                {
                    data: 'first_name',
                    name: 'first_name',
                    class: "p-4"
                },
                {
                    data: 'last_name',
                    name: 'last_name',
                    class: "p-4"
                },
                {
                    data: 'username',
                    name: 'username',
                    class: "p-4"
                },
                {
                    data: 'email',
                    name: 'email',
                    class: "p-4"
                },
                {
                    data: 'phoneNo',
                    name: 'phoneNo',
                    class: "p-4"
                },
                {
                    data: 'role.role_type',
                    name: 'role.role_type',
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

        $('body').on('click', '.addUser', function() {
            $('#passwordField').removeClass('hidden');
            $('#phoneNoField').removeClass('col-span-2');
            $('#user-modal').removeClass('hidden');
            $('#user-modal').addClass('flex');
            $('#saveBtn').html("Add User");
            $('#user_id').val('');
            $('#userForm').trigger("reset");
            $('#modalTitle').html("Create New User");
        });

        $('#closeModal').click(function() {
            $('#user-modal').remove('flex');
            $('#user-modal').addClass('hidden');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Saving..');

            $.ajax({
                data: $('#userForm').serialize(),
                url: "{{ route('user.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {

                    $('#userForm').trigger("reset");
                    $('#user-modal').addClass('hidden');
                    table.draw();

                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Add User');
                }
            });
        });

        $('body').on('click', '.editUser', function() {
            var user_id = $(this).data('id');
            var url = "{{ url('edit-user') }}/" + user_id;

            $.get(url, function(data) {
                $('#modalTitle').html("Edit User");
                $('#saveBtn').html("Save changes");
                $('#user-modal').removeClass('hidden');
                $('#user-modal').addClass('flex');
                $('#passwordField').addClass('hidden');
                $('#phoneNoField').addClass('col-span-2');
                $('#user_id').val(data.user_id);
                $('#first_name').val(data.first_name);
                $('#last_name').val(data.last_name);
                $('#username').val(data.username);
                $('#email').val(data.email);
                $('#phoneNo').val(data.phoneNo);
                $('#role_id').val(data.role_id);
            })
        });



    });
</script>

@endsection