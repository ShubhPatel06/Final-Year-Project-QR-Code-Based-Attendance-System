@extends("layouts.teacherLayout")
@section('sidebar')
<x-student-sidebar focus='lecture' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">
    <x-register-lecture-modal />

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
        });

        $('#closeModal').click(function() {
            $('#register-lectureForm .error-message').remove();

            $('#register-lecture-modal').remove('flex');
            $('#register-lecture-modal').addClass('hidden');
        });
    });
</script>

@endsection