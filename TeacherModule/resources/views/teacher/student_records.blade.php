@extends("layouts.teacherLayout")
@section('sidebar')
<x-teacher-sidebar focus='attendance' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">
    <x-update-attendance-modal />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl ">Student Attendance Details</h1>
        <button id="markAllPresent" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center markAllPresent" type="button">
            Mark All Present
        </button>
    </div>

    <table class="min-w-fug-white shadow-md rounded-lg students-table" id="students-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="p-4 font-semibold text-gray-700">Admission Number</th>
                <th class="p-4 font-semibold text-gray-700">First Name</th>
                <th class="p-4 font-semibold text-gray-700">Last Name</th>
                <th class="p-4 font-semibold text-gray-700">Hours</th>
                <th class="p-4 font-semibold text-gray-700">Attendance</th>
                <th class="p-4 font-semibold text-gray-700">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white">
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(function() {
        function getRecordIdFromUrl() {

            var url = window.location.href;
            var parts = url.split('/');
            return parts[parts.length - 1];
        }

        var recordID = getRecordIdFromUrl();

        var table = $('.students-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('teacher-studentRecords') }}/" + recordID,
            columns: [{
                    data: 'student_adm_no',
                    name: 'student_adm_no',
                    class: "p-4"
                },
                {
                    data: 'student.user.first_name',
                    name: 'student.user.first_name',
                    class: "p-4"
                },
                {
                    data: 'student.user.last_name',
                    name: 'student.user.last_name',
                    class: "p-4"
                },
                {
                    data: 'hours',
                    name: 'hours',
                    class: "p-4"
                },
                {
                    data: 'is_present',
                    name: 'is_present',
                    class: "p-4",
                    render: function(data, type, row) {
                        if (data === null) {
                            return '<span class="bg-amber-400 p-[0.35rem] rounded-md text-white font-semibold">Not Marked</span>';
                        } else if (data === 1) {
                            return '<span class="bg-emerald-400 p-[0.35rem] rounded-md text-white font-semibold">Present</span>';
                        } else if (data === 2) {
                            return '<span class="bg-red-400 p-[0.35rem] rounded-md text-white font-semibold">Absent</span>';
                        } else {
                            return '';
                        }
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

        $('body').on('click', '.updateAttendance', function() {
            var attendance_id = $(this).data('id');
            var url = "{{ url('get-attendance') }}/" + attendance_id;
            $.get(url, function(data) {
                $('#modalTitle').html("Edit Attendance");
                $('#saveBtn').html("Save changes");
                $('#updateAttendance-modal').removeClass('hidden');
                $('#updateAttendance-modal').addClass('flex');
                $('#attendance_id').val(data.attendance_id);
                $('#is_present').val(data.is_present);
            })
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Saving..');
            $.ajax({
                data: $('#updateAttendanceForm').serialize(),
                url: "{{ route('teacher.editAttendance') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    $('#updateAttendanceForm').trigger("reset");
                    $('#updateAttendance-modal').addClass('hidden');
                    table.draw();
                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Save Changes');
                }
            });
        });

        $('#closeModal').click(function() {
            $('#updateAttendance-modal').remove('flex');
            $('#updateAttendance-modal').addClass('hidden');
        });

        $('#markAllPresent').click(function() {
            // Confirm action
            if (confirm('Are you sure you want to mark all students present?')) {
                var csrfToken = "{{ csrf_token() }}";
                var rowData = {
                    "_token": csrfToken,
                    'attendance_record_id': recordID,
                    'is_present': 1, // 1 represents "Present"
                };

                // Update the database via AJAX
                $.ajax({
                    data: rowData,
                    url: "{{ route('teacher.editAllAttendance') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        table.draw();
                    },
                    error: function(data) {
                        alert('Error:', data);
                    }
                });
            }
        });
    });
</script>

@endsection