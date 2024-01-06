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

    <x-time-modal />
    <x-q-r-code-modal />

    <table class="min-w-fug-white shadow-md rounded-lg attendance-table" id="attendance-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="p-4 font-semibold text-gray-700">Lecture</th>
                <th class="p-4 font-semibold text-gray-700">Group</th>
                <th class="p-4 font-semibold text-gray-700">Date</th>
                <th class="p-4 font-semibold text-gray-700">Start Time</th>
                <th class="p-4 font-semibold text-gray-700">End Time</th>
                <th class="p-4 font-semibold text-gray-700">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white">
        </tbody>
    </table>

</div>

<script type="text/javascript">
    $(function() {
        var table = $('.attendance-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('teacher.attendance') }}",
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
                    data: 'date',
                    name: 'date',
                    class: "p-4"
                },
                {
                    data: 'start_time',
                    name: 'start_time',
                    class: "p-4"
                },
                {
                    data: 'end_time',
                    name: 'end_time',
                    class: "p-4"
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

        $('body').on('click', '.generateQR', function() {
            $('#attendance-modal').removeClass('hidden');
            $('#attendance-modal').addClass('flex');
            $('#saveBtn').html("Generate QR Code");
            $('#attendanceForm').trigger("reset");
            $('#modalTitle').html("Generate QR Code");
        });

        $('#closeModal').click(function() {
            $('#attendanceForm .error-message').remove();

            $('#attendance-modal').remove('flex');
            $('#attendance-modal').addClass('hidden');
        });

        document.getElementById('date').min = new Date().toISOString().split("T")[0];

        $('#lecture_id').change(function() {
            var lectureId = $(this).val();

            $.ajax({
                url: '/get-group/' + lectureId,
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

        $('#saveBtn').click(async function(e) {
            e.preventDefault();
            $(this).html('Saving..');

            $.ajax({
                data: $('#attendanceForm').serialize(),
                url: "{{ route('teacher.storeData') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (data.errors) {
                        // Display validation errors in the modal
                        displayErrors(data.errors);
                    } else {
                        // Reset the form and close the modal on success
                        $('#attendanceForm .error-message').remove();

                        $('#attendanceForm').trigger("reset");
                        $('#attendance-modal').addClass('hidden');
                        table.draw();
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', xhr);

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Display validation errors in the modal
                        displayErrors(xhr.responseJSON.errors);
                    } else {

                        var errorMessage = "An error occurred while saving the record. Please try again.";

                        alert(errorMessage);
                    }

                    $('#saveBtn').html('Generate QR Code');

                }
            });

        });

        let timerInterval;
        let record_id;
        let qr_code_path;

        $('body').on('click', '.displayQR', function() {
            $('#time-modal').removeClass('hidden');
            $('#time-modal').addClass('flex');
            $('#TimemodalTitle').html("Time to Display QR Code");
            $('#timeBtn').html("Display QR Code");
            $('#time').val('');

            qr_code_path = $(this).data('qr-code');
            record_id = $(this).data('id');
            var fullUrl = "{{ asset('') }}" + qr_code_path;

            $('#timeBtn').click(function(event) {
                event.preventDefault();
                const timeInput = $('#time').val();

                if (timeInput.trim() !== '' && !isNaN(timeInput) && parseInt(timeInput) > 0) {
                    const timeInMinutes = parseInt(timeInput);
                    const seconds = timeInMinutes * 60;
                    $('#time-modal').addClass('hidden');
                    clearInterval(timerInterval);

                    displayQRCode(fullUrl, seconds);

                } else {
                    console.log('Invalid time input. Please enter a valid positive number.');
                }
            });

        });

        function displayQRCode(fullUrl, seconds) {
            $('#qrCode-modal').removeClass('hidden');
            $('#qrCode-modal').addClass('flex');
            $('#QRmodalTitle').html("Displaying QR Code");
            $('#QRcode').attr('src', fullUrl);

            // Start countdown timer
            timerInterval = setInterval(function() {
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;

                updateTimer(minutes, remainingSeconds);

                seconds--;

                if (seconds < 0) {
                    clearInterval(timerInterval);
                    // Execute function to delete QR code
                    deleteQRCode(record_id);
                }
            }, 1000);
        }

        function updateTimer(minutes, seconds) {
            // Display the timer in the format MM:SS
            const timerDisplay = document.getElementById('countdownTimer');
            timerDisplay.textContent = `${padZero(minutes)}:${padZero(seconds)}`;
        }

        // Function to add leading zero to single-digit numbers
        function padZero(num) {
            return num < 10 ? `0${num}` : num;
        }

        $('body').on('click', '.deleteQR', function() {
            record_id = $(this).data('id');

            if (confirm("Are you sure you want to delete?")) {
                deleteQRCode(record_id);
            }

        });

        function deleteQRCode(qrCodeId) {
            $.ajax({
                url: `/delete-qrcode/${qrCodeId}`,
                type: 'DELETE',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
                success: function(data) {
                    $('#qrCode-modal').removeClass('flex');
                    $('#qrCode-modal').addClass('hidden');
                    table.draw();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        $('#closeQRModal').click(function() {
            clearInterval(timerInterval);

            $('#qrCode-modal').removeClass('flex');
            $('#qrCode-modal').addClass('hidden');
            updateTimer(0, 0);
        });

        $('#closeTimeModal').click(function() {
            $('#time-modal').removeClass('flex');
            $('#time-modal').addClass('hidden');
        });

        function displayErrors(errors) {
            // Remove any existing error messages
            $('#userForm .error-message').remove();

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