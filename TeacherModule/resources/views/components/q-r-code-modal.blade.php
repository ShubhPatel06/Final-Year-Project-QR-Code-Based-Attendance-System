<!-- Main modal -->
<div id="qrCode-modal" tabindex="-1" aria-hidden="true" class="fixed hidden backdrop-blur-[2px] items-center justify-center top-0 left-1/2 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full shadow-xl">
    <div class="relative w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow ">
            <button type="button" id="closeQRModal" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="qrCode-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="px-6 py-6 lg:px-8 flex flex-col items-center">
                <h3 class="mb-8 text-2xl font-medium text-gray-900 " id="QRmodalTitle"></h3>
                <img id="QRcode" src="" alt="QR Code" class="mb-4">
                <p class="text-red-600 text-2xl mb-4 font-bold">Expires in</p>
                <div id="countdownTimer" class="text-3xl mb-2"></div>
            </div>
        </div>
    </div>
</div>