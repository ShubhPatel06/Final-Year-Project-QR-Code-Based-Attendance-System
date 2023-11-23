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

    <div id="locationExplanationModal" tabindex="-1" aria-hidden="true" class="fixed hidden backdrop-blur-[2px] items-center justify-center top-0 left-1/2 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full shadow-xl">
        <div class="relative w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow ">
                <button type="button" id="closeExplanationModal" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="locationExplanationModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="px-6 py-6 lg:px-8">
                    <h2 class="text-xl font-bold mb-4">Why do we need your location?</h2>
                    <p class="text-gray-700">
                        We use your location to provide you with personalized services, such as attendance tracking based on your current location.
                    </p>
                </div>
            </div>
        </div>
    </div>
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

        // $('#saveBtn').click(function(e) {
        //     e.preventDefault();
        //     $(this).html('Saving..');

        //     $.ajax({
        //         data: $('#attendanceForm').serialize(),
        //         url: "{{ route('teacher.storeData') }}",
        //         type: "POST",
        //         dataType: 'json',
        //         success: function(data) {
        //             $('#attendanceForm').trigger("reset");
        //             $('#attendance-modal').addClass('hidden');
        //             table.draw();
        //         },
        //         error: function(data) {
        //             console.log('Error:', data);
        //             $('#saveBtn').html('Generate QR Code');
        //         }
        //     });
        // });
        function getLocation() {
            return new Promise((resolve, reject) => {
                if (navigator.permissions) {
                    // Use Permissions API to check and request location permission
                    navigator.permissions.query({
                        name: 'geolocation'
                    }).then(permissionStatus => {
                        if (permissionStatus.state === 'granted') {
                            // If permission is already granted, get the current position
                            navigator.geolocation.getCurrentPosition(
                                position => resolve(position),
                                error => reject(error)
                            );
                        } else if (permissionStatus.state === 'prompt') {
                            // If permission is not yet determined, request it
                            navigator.geolocation.getCurrentPosition(
                                position => resolve(position),
                                error => reject(error)
                            );
                        } else {
                            // Permission is denied
                            reject('Geolocation permission denied');
                        }
                    });
                } else if (navigator.geolocation) {
                    // For browsers not supporting Permissions API
                    navigator.geolocation.getCurrentPosition(
                        position => resolve(position),
                        error => reject(error)
                    );
                } else {
                    // Geolocation is not supported by this browser
                    reject('Geolocation is not supported');
                }
            });
        }

        $('#saveBtn').click(async function(e) {
            e.preventDefault();
            $(this).html('Saving..');

            // Get the selected session type
            const sessionType = $('#session_type').val();

            // Check if the session type is physical
            if (sessionType === 'physical') {
                try {
                    // Display the location explanation modal
                    $('#locationExplanationModal').removeClass('hidden');
                    $('#locationExplanationModal').addClass('flex');

                    // Get the user's location
                    const position = await getLocation();

                    // Extract latitude and longitude from the position data
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    // Append location data to the form
                    const formData = new FormData($('#attendanceForm')[0]);
                    formData.append('latitude', latitude);
                    formData.append('longitude', longitude);

                    // Submit the form with location data
                    $.ajax({
                        data: formData,
                        url: "{{ route('teacher.storeData') }}",
                        type: "POST",
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            $('#attendanceForm').trigger("reset");
                            $('#attendance-modal').addClass('hidden');
                            $('#locationExplanationModal').addClass('hidden'); // Hide the location explanation modal after successful submission
                            table.draw();
                        },
                        error: function(data) {
                            console.log('Error:', data);
                            $('#saveBtn').html('Generate QR Code');
                            $('#locationExplanationModal').addClass('hidden'); // Hide the location explanation modal on error
                        }
                    });
                } catch (error) {
                    console.error('Error getting location:', error);

                    // Handle error, e.g., display a message to the user
                    if (error === 'Geolocation permission denied') {
                        // Prompt the user to enable location
                        alert('Please enable location permission to use this feature.');
                    }

                    $('#locationExplanationModal').addClass('hidden');
                    $('#locationExplanationModal').removeClass('flex');
                }
            } else {
                // If the session type is not physical, submit the form without location data
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
            }
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

        function deleteQRCode(qrCodeId) {
            // Call the Laravel route to delete the QR code
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

        $('#closeExplanationModal').click(function() {
            $('#locationExplanationModal').addClass('hidden');
            $('#locationExplanationModal').removeClass('flex');
        });
    });
</script>

@endsection