<div id="main-nav" class="pt-20 z-40 hidden w-[50%] top-0 left-0 md:flex md:w-[18%] fixed h-[100vh] bg-gray-400 border-r-2">
    <div class="flex flex-col gap-y-2 mt-2 items-center w-full text-sm my-8 px-8">
        <div class="{{$focus == 'dashboard' ? 'bg-blue-600 text-white px-3':'hover:font-semibold' }} flex flex-row h-12 items-center gap-4 text-sm mx-4 lg:text-base rounded drop-shadow">
            <i class="fa-solid fa-house"></i>
            <p class=""><a href="{{route('student.dashboard')}}">Dashboard</a></p>
        </div>
        <div class="{{ $focus=='lecture' ? 'bg-blue-500 text-white px-3' : 'text-black' }} flex flex-row h-12 items-center gap-4 text-sm mx-4 lg:text-base rounded drop-shadow">
            <i class="fa-solid fa-book"></i>

            <p class=""><a href="{{route('student.lectures')}}">Lectures</a></p>
        </div>
    </div>
</div>

<div id="mobile-nav" class="pt-5 z-40 hidden w-[60%] top-0 left-0 md:hidden fixed h-[100vh] bg-gray-500">
    <div id="close-nav" class="flex text-2xl md:hidden absolute top-6 left-3 hover:cursor-pointer mb-4">
        <i class="fa-solid fa-xmark"></i>
    </div>
    <div class="flex flex-col gap-4 items-start text-blue-strath w-full text-sm mt-10 px-5">
        <div class="{{$focus == 'dashboard' ? 'bg-blue-600 text-white':'hover:font-semibold' }} flex flex-row h-12 items-center gap-4 text-sm mx-4 lg:text-base rounded drop-shadow">
            <i class="fa-solid fa-house"></i>
            <p class=""><a href="{{route('student.dashboard')}}">Dashboard</a></p>
        </div>
        <div class="{{ $focus=='lecture' ? 'bg-blue-500 text-white px-3' : 'text-black' }} flex flex-row h-12 items-center gap-4 text-sm mx-4 lg:text-base rounded drop-shadow">
            <i class="fa-solid fa-book"></i>

            <p class=""><a href="{{route('student.lectures')}}">Lectures</a></p>
        </div>
    </div>
</div>

<div id="mobile-toggle" class="fixed md:hidden text-white left-3 z-50 w-14 h-10 top-3 rounded-md flex justify-center shadow-md items-end bg-blue-500 p-2 toggle-button">
    @switch($focus)
    @case('dashboard')
    <i class="fa-solid fa-house"></i>
    @break
    @case('lecture')
    <i class="fa-solid fa-book"></i>
    @break
    @default
    @endswitch

</div>

<script>
    $(document).ready(function() {
        const $toggleButton = $(".toggle-button");

        $("#mobile-toggle").click(function(e) {
            $("#mobile-nav").slideDown();
            $toggleButton.hide();
        });

        $("#close-nav").click(function(e) {
            $("#mobile-nav").slideUp();
            $toggleButton.show();
        });

    });
</script>