<!-- Main modal -->
<div id="group-modal" tabindex="-1" aria-hidden="true" class="fixed hidden backdrop-blur-[2px] items-center justify-center top-0 left-1/2 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full shadow-xl">
    <div class="relative w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow ">
            <button type="button" id="closeModal" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="group-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="px-6 py-6 lg:px-8">
                <h3 class="mb-4 text-xl font-medium text-gray-900 " id="modalTitle"></h3>
                <form class="space-y-6" action="#" method="POST" id="groupForm">
                    @csrf
                    <input type="hidden" name="group_id" id="group_id">
                    <div>
                        <label for="group_name" class="block mb-2 text-sm font-medium text-gray-900 ">Group Name</label>
                        <input type="text" name="group_name" id="group_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  " required>
                    </div>
                    <div class="col-span-2">
                        <label for="division_id" class="block mb-2 text-sm font-medium text-gray-900">Course Division</label>
                        <select name="division_id" id="division_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                            <option value="" selected disabled>Select Course Division</option>
                            @foreach ($divisions as $division)
                            <option value="{{ $division->division_id }}">{{ $division->division_name}} </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="year" class="block mb-2 text-sm font-medium text-gray-900 ">Year</label>
                        <input type="number" min=1 max=4 name="year" id="year" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  " required>
                    </div>
                    <div>
                        <label for="semester" class="block mb-2 text-sm font-medium text-gray-900 ">Semester</label>
                        <input type="number" min=1 max=3 name="semester" id="semester" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  " required>
                    </div>
                    <button type="submit" id="saveBtn" class="w-full text-white bg-emerald-700 hover:bg-emerald-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center "></button>
                </form>
            </div>
        </div>
    </div>
</div>