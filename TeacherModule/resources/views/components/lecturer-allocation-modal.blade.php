<!-- Main modal -->
<div id="lecturer_allocation-modal" tabindex="-1" aria-hidden="true" class="fixed hidden backdrop-blur-[2px] items-center justify-center top-0 left-1/2 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full shadow-xl">
    <div class="relative w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow ">
            <button type="button" id="closeModal" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="lecturer_allocation-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="px-6 py-6 lg:px-8">
                <h3 class="mb-4 text-xl font-medium text-gray-900 " id="modalTitle"></h3>
                <form class="space-y-6" action="#" method="POST" id="lecturer_allocationForm">
                    @csrf
                    <input type="hidden" id="lecturerID">
                    <input type="hidden" id="lectureID">
                    <input type="hidden" id="groupID">
                    <div class="col-span-2">
                        <label for="lecturer_id" class="block mb-2 text-sm font-medium text-gray-900">Lecturer</label>
                        <select name="lecturer_id" id="lecturer_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                            <option value="" selected disabled>Select Lecturer</option>
                            @foreach ($lecturers as $lecturer)
                            <option value="{{ $lecturer->user_id }}">{{ $lecturer->user->first_name}} {{ $lecturer->user->last_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label for="lecture_id" class="block mb-2 text-sm font-medium text-gray-900">Lecture</label>
                        <select name="lecture_id" id="lecture_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                            <option value="" selected disabled>Select Lecture</option>
                            @foreach ($lectures as $lecture)
                            <option value="{{ $lecture->lecture_id }}">{{ $lecture->lecture_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label for="group_id" class="block mb-2 text-sm font-medium text-gray-900">Group</label>
                        <select name="group_id" id="group_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                            <option value="" selected disabled>Select Group</option>
                        </select>
                    </div>
                    <button type="submit" id="saveBtn" class="w-full text-white bg-emerald-700 hover:bg-emerald-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>