<!-- Main modal -->
<div id="student-modal" tabindex="-1" aria-hidden="true" class="fixed hidden backdrop-blur-[2px] items-center justify-center top-0 left-1/2 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full shadow-xl">
    <div class="relative w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow ">
            <button type="button" id="closeModal" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="student-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="px-6 py-6 lg:px-8">
                <h3 class="mb-4 text-xl font-medium text-gray-900 " id="modalTitle"></h3>
                <form class="space-y-6" action="#" method="POST" id="studentForm">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    <input type="hidden" name="adm_no" id="adm_no">
                    <div id="onlyAdd">
                        <div class="mb-5">
                            <label for="user_id" class="block mb-2 text-sm font-medium text-gray-900 ">Select User</label>
                            <select name="user_id" id="user_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                <option value="" selected disabled>Select User</option>
                                @foreach ($users as $user)
                                {
                                <option value="{{ $user->user_id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                }
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-5">
                            <label for="course_id" class="block mb-2 text-sm font-medium text-gray-900 ">Select Course</label>
                            <select name="course_id" id="course_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                <option value="" selected disabled>Select Course</option>
                                @foreach ($courses as $course)
                                {
                                <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                                }
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" id="saveBtn" class="w-full text-white bg-emerald-700 hover:bg-emerald-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center "></button>
                    </div>
                    <div id="onlyEdit" class="hidden grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 ">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  " required>
                        </div>
                        <div>
                            <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 ">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  " required>
                        </div>
                        <div>
                            <label for="username" class="block mb-2 text-sm font-medium text-gray-900 ">Username</label>
                            <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  " required>
                        </div>
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 ">Email</label>
                            <input type="text" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  " required>
                        </div>
                        <div>
                            <label for="phoneNo" class="block mb-2 text-sm font-medium text-gray-900 ">Phone Number</label>
                            <input type="text" name="phoneNo" id="phoneNo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  " required>
                        </div>
                        <div>
                            <label for="edit_course_id" class="block mb-2 text-sm font-medium text-gray-900 ">Select Course</label>
                            <select name="edit_course_id" id="edit_course_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                <option value="" selected disabled>Select Course</option>
                                @foreach ($courses as $course)
                                {
                                <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                                }
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" id="updateBtn" class="w-full col-span-2 text-white bg-emerald-700 hover:bg-emerald-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center "></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>