@extends("layouts.adminLayout")
@section('sidebar')
<x-admin-sidebar focus='roles' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">
    <h1 class="text-3xl mb-6">Roles Details</h1>
    <table class="min-w-full table-auto text-left border-collapse border-slate-900 bg-white shadow-md rounded-lg roles-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="border border-slate-900 p-4 font-semibold text-gray-700">Role ID</th>
                <th class="border border-slate-900 p-4 font-semibold text-gray-700">Role Tyoe</th>
                <th class="border border-slate-900 p-4 font-semibold text-gray-700">Action</th>
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
                    class: "border border-slate-900 p-4"
                },
                {
                    data: 'role_type',
                    name: 'role_type',
                    class: "border border-slate-900 p-4"
                },
                {
                    data: 'action',
                    name: 'action',
                    class: "border border-slate-900 p-4",
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('body').on('click', '.editRole', function() {
            var role_id = $(this).data('id');

            alert(role_id);
        });



    });
</script>

@endsection