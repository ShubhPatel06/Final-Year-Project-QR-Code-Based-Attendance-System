@extends("layouts.adminLayout")
@section('sidebar')
<x-admin-sidebar focus='faculty' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">
    <x-faculty-modal />

    <div class="flex items-center justify-between">
        <h1 class="text-3xl mb-6">Faculty Details</h1>

        <!-- Modal toggle -->
        <button data-modal-target="faculty-modal" data-modal-toggle="faculty-modal" class="block text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center addFaculty" type="button">
            Add Faculty
        </button>

    </div>
    <table class="min-w-full table-auto text-left border-collapse border-slate-900 bg-white shadow-md rounded-lg faculties-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="border border-slate-900 p-4 font-semibold text-gray-700">Faculty ID</th>
                <th class="border border-slate-900 p-4 font-semibold text-gray-700">Faculty Name</th>
                <th class="border border-slate-900 p-4 font-semibold text-gray-700">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white">
        </tbody>
    </table>

</div>

<script type="text/javascript">
    $(function() {

        var table = $('.faculties-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.faculties') }}",
            columns: [{
                    data: 'faculty_id',
                    name: 'faculty_id',
                    class: "border border-slate-900 p-4"
                },
                {
                    data: 'faculty_name',
                    name: 'faculty_name',
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

        $('body').on('click', '.addFaculty', function() {
            $('#faculty-modal').removeClass('hidden');
            $('#faculty-modal').addClass('flex');
            $('#saveBtn').html("Add Faculty");
            $('#faculty_id').val('');
            $('#facultyForm').trigger("reset");
            $('#modalTitle').html("Create New Faculty");
        });

        $('#closeModal').click(function() {
            $('#faculty-modal').remove('flex');
            $('#faculty-modal').addClass('hidden');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Saving..');

            $.ajax({
                data: $('#facultyForm').serialize(),
                url: "{{ route('faculty.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {

                    $('#facultyForm').trigger("reset");
                    $('#faculty-modal').addClass('hidden');
                    table.draw();

                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Add Faculty');
                }
            });
        });

        $('body').on('click', '.editFaculty', function() {
            var faculty_id = $(this).data('id');
            var url = "{{ url('edit-faculty') }}/" + faculty_id;
            $.get(url, function(data) {
                $('#modalTitle').html("Edit Faculty");
                $('#saveBtn').html("Save changes");
                $('#faculty-modal').removeClass('hidden');
                $('#faculty-modal').addClass('flex');
                $('#faculty_id').val(data.faculty_id);
                $('#faculty_name').val(data.faculty_name);
            })
        });


        $('body').on('click', '.deleteFaculty', function() {
            var faculty_id = $(this).data("id");
            var url = "{{ url('delete-faculty') }}/" + faculty_id;
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