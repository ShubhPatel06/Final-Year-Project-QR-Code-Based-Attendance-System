<!-- Main modal -->
<div id="lecture-modal" tabindex="-1" aria-hidden="true" class="fixed hidden backdrop-blur-[2px] items-center justify-center top-0 left-1/2 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full shadow-xl">
    <div class="relative w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow ">
            <button type="button" id="closeModal" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="lecture-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="px-6 py-6 lg:px-8">
                <h3 class="mb-4 text-xl font-medium text-gray-900 " id="modalTitle"></h3>
                <form class="space-y-6" action="#" method="POST" id="lectureForm">
                    @csrf
                    <input type="hidden" name="lecture_id" id="lecture_id">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="lecture_code" class="block mb-2 text-sm font-medium text-gray-900">Lecture Code</label>
                            <input type="text" name="lecture_code" id="lecture_code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label for="lecture_name" class="block mb-2 text-sm font-medium text-gray-900">Lecture Name</label>
                            <input type="text" name="lecture_name" id="lecture_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label for="total_hours" class="block mb-2 text-sm font-medium text-gray-900">Total Hours</label>
                            <input type="number" name="total_hours" id="total_hours" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label for="day" class="block mb-2 text-sm font-medium text-gray-900">Day</label>
                            <input type="text" name="day" id="day" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label for="start_time" class="block mb-2 text-sm font-medium text-gray-900">Start Time</label>
                            <input type="time" name="start_time" id="start_time" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label for="end_time" class="block mb-2 text-sm font-medium text-gray-900">End Time</label>
                            <input type="time" name="end_time" id="end_time" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div class="col-span-2">
                            <label for="course_id" class="block mb-2 text-sm font-medium text-gray-900">Course</label>
                            <select name="course_id" id="course_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                <option value="" selected disabled>Select Course</option>
                                @foreach ($courses as $course)
                                <option value="{{ $course->course_id }}">{{ $course->course_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label for="lecturer_id" class="block mb-2 text-sm font-medium text-gray-900">Lecturer</label>
                            <select name="lecturer_id" id="lecturer_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                <option value="" selected disabled>Select Lecturer</option>
                                @foreach ($lecturers as $lecturer)
                                <option value="{{ $lecturer->user_id }}">{{ $lecturer->user->first_name}} {{ $lecturer->user->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" id="saveBtn" class="w-full text-white bg-emerald-700 hover:bg-emerald-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Save</button>
                </form>

            </div>
        </div>
    </div>
</div>