@extends("layouts.adminLayout")
@section('sidebar')
<x-admin-sidebar focus='roles' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">
    <x-role-modal />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl ">Roles Details</h1>

        <!-- Modal toggle -->
        <button data-modal-target="role-modal" data-modal-toggle="role-modal" class="block text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center addRole" type="button">
            Add Role
        </button>

    </div>
    <table class="min-w-fug-white shadow-md rounded-lg roles-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="p-4 font-semibold text-gray-700">Role ID</th>
                <th class="p-4 font-semibold text-gray-700">Role Type</th>
                <th class="p-4 font-semibold text-gray-700">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white">
        </tbody>
    </table>

</div>

<script type="text/javascript">
    $(function() {

        var table = $('.roles-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.roles') }}",
            columns: [{
                    data: 'role_id',
                    name: 'role_id',
                    class: "p-4"
                },
                {
                    data: 'role_type',
                    name: 'role_type',
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

        $('body').on('click', '.addRole', function() {
            $('#role-modal').removeClass('hidden');
            $('#role-modal').addClass('flex');
            $('#saveBtn').html("Add Role");
            $('#role_id').val('');
            $('#roleForm').trigger("reset");
            $('#modalTitle').html("Create New Role");
        });

        $('#closeModal').click(function() {
            $('#role-modal').remove('flex');
            $('#role-modal').addClass('hidden');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Saving..');

            $.ajax({
                data: $('#roleForm').serialize(),
                url: "{{ route('role.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {

                    $('#roleForm').trigger("reset");
                    $('#role-modal').addClass('hidden');
                    table.draw();

                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Add Role');
                }
            });
        });

        $('body').on('click', '.editRole', function() {
            var role_id = $(this).data('id');
            var url = "{{ url('edit-role') }}/" + role_id;
            $.get(url, function(data) {
                $('#modalTitle').html("Edit Role");
                $('#saveBtn').html("Save changes");
                $('#role-modal').removeClass('hidden');
                $('#role-modal').addClass('flex');
                $('#role_id').val(data.role_id);
                $('#role_type').val(data.role_type);
            })
        });


        $('body').on('click', '.deleteRole', function() {
            var role_id = $(this).data("id");
            var url = "{{ url('delete-role') }}/" + role_id;
            if (confirm("Are you sure you want to delete?")) {
                var csrfToken = "{{ csrf_token() }}";
                $.ajax({
                    type: "DELETE",
                    url: url,
                    data: {
                        "_token": csrfToken
                    },
                    success: function(data) {
                        table.draw();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }
        });

    });
</script>

@endsection