@extends("layouts.adminLayout")
@section('sidebar')
<x-admin-sidebar focus='lecture_group' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">
    <x-lecture-group-modal :lectures='$lectures' :groups='$groups' />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl ">Lecture Groups Details</h1>

        <!-- Modal toggle -->
        <button data-modal-target="lecture_group-modal" data-modal-toggle="lecture_group-modal" class="block text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center addLectureGroup" type="button">
            Add Lecture Group
        </button>

    </div>
    <table class="min-w-fug-white shadow-md rounded-lg lecture_groups-table">
        <thead>
            <tr class="bg-slate-200">
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

        var table = $('.lecture_groups-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.lecture_groups') }}",
            columns: [{
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

        $('body').on('click', '.addLectureGroup', function() {
            $('#lecture_group-modal').removeClass('hidden');
            $('#lecture_group-modal').addClass('flex');
            $('#saveBtn').html("Add Lecture Group");
            $('#saveBtn').show();
            $('#updateBtn').hide();
            $('#lecture_id').val('');
            $('#group_id').val('');
            $('#lecture_groupForm').trigger("reset");
            $('#modalTitle').html("Create New Lecture Group");
        });

        $('#closeModal').click(function() {
            $('#lecture_group-modal').remove('flex');
            $('#lecture_group-modal').addClass('hidden');
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Saving..');

            $.ajax({
                data: $('#lecture_groupForm').serialize(),
                url: "{{ route('lecture_group.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {

                    $('#lecture_groupForm').trigger("reset");
                    $('#lecture_group-modal').addClass('hidden');
                    table.draw();

                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Add Lecture Group');
                }
            });
        });

        $('body').on('click', '.deleteLectureGroup', function() {
            var group_id = $(this).data("id");
            var lecture_id = $(this).data("del");
            var url = "{{ url('delete-lecture_group') }}/" + lecture_id;
            if (confirm("Are you sure you want to delete?")) {
                var csrfToken = "{{ csrf_token() }}";
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('lecture_group.delete') }}",
                    data: {
                        "_token": csrfToken,
                        'group_id': group_id,
                        'lecture_id': lecture_id,
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