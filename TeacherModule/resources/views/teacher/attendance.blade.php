@extends("layouts.teacherLayout")
@section('sidebar')
<x-teacher-sidebar focus='attendance' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">
    <x-attendance-modal :lectures='$lectures' />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl ">Attendance Details</h1>

        <!-- Modal toggle -->
        <button data-modal-target="attendance-modal" data-modal-toggle="attendance-modal" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center generateQR" type="button">
            Generate QR Code
        </button>

    </div>
    <img src="{{ asset('qrcodes/qrcode_655caddc025c2.png') }}" alt="QR Code">
    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="300" height="300" viewBox="0 0 300 300">
        <rect x="0" y="0" width="300" height="300" fill="#ffffff" />
        <g transform="scale(9.091)">
            <g transform="translate(0,0)">
                <path fill-rule="evenodd" d="M8 0L8 4L9 4L9 5L8 5L8 7L9 7L9 6L10 6L10 9L11 9L11 8L12 8L12 9L13 9L13 8L12 8L12 7L13 7L13 6L14 6L14 8L16 8L16 7L17 7L17 9L14 9L14 12L15 12L15 13L12 13L12 12L13 12L13 11L12 11L12 12L11 12L11 13L9 13L9 12L10 12L10 11L11 11L11 10L9 10L9 9L7 9L7 8L4 8L4 9L3 9L3 10L2 10L2 8L0 8L0 10L1 10L1 11L0 11L0 13L1 13L1 11L2 11L2 14L3 14L3 15L1 15L1 14L0 14L0 16L3 16L3 18L0 18L0 22L2 22L2 24L0 24L0 25L2 25L2 24L3 24L3 25L8 25L8 26L9 26L9 27L8 27L8 29L9 29L9 30L10 30L10 29L11 29L11 31L8 31L8 33L9 33L9 32L10 32L10 33L12 33L12 32L11 32L11 31L12 31L12 30L13 30L13 31L14 31L14 33L20 33L20 32L19 32L19 31L18 31L18 30L16 30L16 29L19 29L19 30L20 30L20 31L21 31L21 33L22 33L22 32L23 32L23 33L26 33L26 32L28 32L28 33L29 33L29 32L30 32L30 33L31 33L31 32L30 32L30 31L32 31L32 30L33 30L33 27L32 27L32 26L33 26L33 25L30 25L30 26L29 26L29 24L30 24L30 23L32 23L32 21L33 21L33 20L32 20L32 17L33 17L33 15L32 15L32 14L31 14L31 13L30 13L30 12L29 12L29 10L31 10L31 11L32 11L32 10L31 10L31 9L33 9L33 8L29 8L29 10L28 10L28 8L27 8L27 10L28 10L28 12L25 12L25 13L24 13L24 12L23 12L23 16L24 16L24 17L22 17L22 11L24 11L24 10L25 10L25 8L24 8L24 7L25 7L25 3L23 3L23 2L21 2L21 1L20 1L20 0L19 0L19 2L21 2L21 3L22 3L22 5L21 5L21 4L20 4L20 5L19 5L19 3L18 3L18 2L15 2L15 3L13 3L13 2L14 2L14 0L13 0L13 1L11 1L11 2L12 2L12 5L13 5L13 6L12 6L12 7L11 7L11 3L10 3L10 2L9 2L9 0ZM16 0L16 1L17 1L17 0ZM22 0L22 1L24 1L24 2L25 2L25 0ZM17 3L17 4L16 4L16 5L15 5L15 7L16 7L16 6L17 6L17 7L18 7L18 5L17 5L17 4L18 4L18 3ZM13 4L13 5L14 5L14 4ZM22 5L22 6L21 6L21 7L22 7L22 6L23 6L23 7L24 7L24 5ZM19 6L19 8L18 8L18 9L19 9L19 11L20 11L20 13L19 13L19 12L18 12L18 13L17 13L17 11L15 11L15 12L16 12L16 13L17 13L17 14L16 14L16 16L15 16L15 17L14 17L14 14L13 14L13 15L12 15L12 16L11 16L11 14L12 14L12 13L11 13L11 14L10 14L10 17L9 17L9 15L8 15L8 13L6 13L6 14L7 14L7 15L6 15L6 16L7 16L7 17L6 17L6 18L3 18L3 19L1 19L1 20L2 20L2 21L3 21L3 22L4 22L4 23L3 23L3 24L4 24L4 23L5 23L5 24L7 24L7 23L5 23L5 20L8 20L8 19L11 19L11 20L10 20L10 22L9 22L9 21L8 21L8 22L9 22L9 23L10 23L10 22L12 22L12 21L11 21L11 20L12 20L12 19L13 19L13 21L14 21L14 20L16 20L16 21L15 21L15 22L13 22L13 24L9 24L9 25L14 25L14 26L13 26L13 27L12 27L12 28L11 28L11 26L10 26L10 28L11 28L11 29L14 29L14 31L15 31L15 32L16 32L16 31L15 31L15 29L16 29L16 28L15 28L15 29L14 29L14 27L15 27L15 25L16 25L16 27L17 27L17 26L18 26L18 27L20 27L20 28L19 28L19 29L20 29L20 28L21 28L21 23L20 23L20 22L22 22L22 21L24 21L24 22L25 22L25 23L24 23L24 24L23 24L23 23L22 23L22 29L21 29L21 31L22 31L22 29L23 29L23 31L24 31L24 32L26 32L26 31L28 31L28 32L29 32L29 31L28 31L28 30L30 30L30 29L32 29L32 27L31 27L31 26L30 26L30 29L27 29L27 30L24 30L24 24L25 24L25 23L26 23L26 24L28 24L28 23L29 23L29 22L31 22L31 21L32 21L32 20L31 20L31 18L29 18L29 17L31 17L31 15L29 15L29 14L30 14L30 13L28 13L28 14L27 14L27 13L25 13L25 16L26 16L26 14L27 14L27 17L26 17L26 18L25 18L25 17L24 17L24 19L25 19L25 20L23 20L23 19L22 19L22 21L21 21L21 20L20 20L20 19L21 19L21 18L22 18L22 17L18 17L18 18L17 18L17 19L18 19L18 20L20 20L20 21L19 21L19 22L18 22L18 21L17 21L17 20L16 20L16 19L15 19L15 18L16 18L16 17L17 17L17 14L18 14L18 13L19 13L19 16L21 16L21 15L20 15L20 14L21 14L21 11L22 11L22 9L24 9L24 8L22 8L22 9L21 9L21 8L20 8L20 6ZM19 8L19 9L20 9L20 8ZM6 9L6 10L3 10L3 14L5 14L5 13L4 13L4 11L5 11L5 12L7 12L7 11L6 11L6 10L7 10L7 9ZM8 11L8 12L9 12L9 11ZM32 12L32 13L33 13L33 12ZM3 15L3 16L5 16L5 15ZM7 15L7 16L8 16L8 15ZM12 16L12 17L13 17L13 16ZM28 16L28 17L27 17L27 20L25 20L25 21L27 21L27 22L28 22L28 21L30 21L30 19L28 19L28 17L29 17L29 16ZM8 17L8 18L6 18L6 19L8 19L8 18L9 18L9 17ZM10 17L10 18L11 18L11 17ZM13 18L13 19L14 19L14 18ZM4 19L4 20L3 20L3 21L4 21L4 20L5 20L5 19ZM6 21L6 22L7 22L7 21ZM15 22L15 24L14 24L14 25L15 25L15 24L16 24L16 25L19 25L19 26L20 26L20 23L19 23L19 24L18 24L18 22L17 22L17 24L16 24L16 22ZM25 25L25 28L28 28L28 25ZM26 26L26 27L27 27L27 26ZM32 32L32 33L33 33L33 32ZM0 0L0 7L7 7L7 0ZM1 1L1 6L6 6L6 1ZM2 2L2 5L5 5L5 2ZM26 0L26 7L33 7L33 0ZM27 1L27 6L32 6L32 1ZM28 2L28 5L31 5L31 2ZM0 26L0 33L7 33L7 26ZM1 27L1 32L6 32L6 27ZM2 28L2 31L5 31L5 28Z" fill="#000000" />
            </g>
        </g>
    </svg>


    <table class="min-w-fug-white shadow-md rounded-lg attendance-table">
        <thead>
            <tr class="bg-slate-200">

            </tr>
        </thead>
        <tbody class="bg-white">
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(function() {
        $('body').on('click', '.generateQR', function() {
            $('#attendance-modal').removeClass('hidden');
            $('#attendance-modal').addClass('flex');
            $('#saveBtn').html("Generate QR Code");
            $('#attendanceForm').trigger("reset");
            $('#modalTitle').html("Generate QR Code");
        });

        $('#closeModal').click(function() {
            $('#attendance-modal').remove('flex');
            $('#attendance-modal').addClass('hidden');
        });

        document.getElementById('date').min = new Date().toISOString().split("T")[0];


        $('#lecture_id').change(function() {
            var lectureId = $(this).val();

            $.ajax({
                url: '/get-groups/' + lectureId,
                type: 'GET',
                success: function(data) {
                    $('#group_id').empty();

                    $('#group_id').append('<option value="" selected disabled>Select Group</option>');

                    $.each(data, function(index, group) {
                        $('#group_id').append('<option value="' + group.group_id + '"> ' + group.group_name + '</option>');
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
                data: $('#attendanceForm').serialize(),
                url: "{{ route('teacher.storeData') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    $('#attendanceForm').trigger("reset");
                    $('#attendance-modal').addClass('hidden');
                    table.draw();
                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Generate QR Code');
                }
            });
        });
    });
</script>

@endsection