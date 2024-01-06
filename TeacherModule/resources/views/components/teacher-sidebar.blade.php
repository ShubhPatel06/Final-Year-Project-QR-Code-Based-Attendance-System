<div id="main-nav" class="z-40 hidden w-[50%] top-0 left-0 md:flex md:w-[18%] flex-col fixed h-[100vh] bg-slate-400 border-r-2">
    <div class="pl-8 pt-4 text-2xl md:block">Attendance System</div>
    <hr class="border-2 border-slate-500 mt-4">


    <div class="flex flex-col gap-y-2 mt-6 items-start w-full text-sm my-8 px-8">
        <div class="{{$focus == 'dashboard' ? 'bg-blue-600 text-white px-3':'hover:font-semibold' }} flex flex-row h-12 items-center gap-4 text-sm mx-4 lg:text-base rounded drop-shadow">
            <i class="fa-solid fa-house"></i>
            <p class=""><a href="{{route('teacher.dashboard')}}">Dashboard</a></p>
        </div>
        <div class="{{ $focus=='lecture_group' ? 'bg-blue-600 text-white px-3' : 'text-black' }} flex flex-row h-12 items-center gap-4 text-sm mx-4 lg:text-base rounded drop-shadow">
            <i class="fa-solid fa-book"></i>
            <p class=""><a href="{{route('teacher.lecture_groups')}}">Lectures & Groups</a></p>
        </div>
        <div class="{{ $focus=='attendance' ? 'bg-blue-600 text-white px-3' : 'text-black' }} flex flex-row h-12 items-center space-x-4 text-sm mx-4 lg:text-base rounded drop-shadow">
            <i class="fa-solid fa-clipboard-user"></i>
            <p class=""><a href="{{route('teacher.attendance')}}">Attendance</a></p>
        </div>
    </div>
</div>

<div id="mobile-nav" class="pt-5 z-40 hidden w-[60%] top-0 left-0 md:hidden fixed h-[100vh] bg-slate-500">
    <div id="close-nav" class="flex text-2xl md:hidden absolute top-6 left-3 hover:cursor-pointer mb-4">
        <i class="fa-solid fa-xmark"></i>
    </div>
    <div class="flex flex-col gap-4 items-start text-blue-strath w-full text-sm mt-10 px-5">
        <div class="{{$focus == 'dashboard' ? 'bg-blue-600 text-white':'hover:font-semibold' }} flex flex-row h-12 items-center gap-4 text-sm mx-4 lg:text-base rounded drop-shadow">
            <i class="fa-solid fa-house"></i>
            <p class=""><a href="{{route('teacher.dashboard')}}">Dashboard</a></p>
        </div>
        <div class="{{ $focus=='lecture_group' ? 'bg-blue-600 text-white px-3' : 'text-black' }} flex flex-row h-12 items-center gap-4 text-sm mx-4 lg:text-base rounded drop-shadow">
            <i class="fa-solid fa-book"></i>
            <p class=""><a href="{{route('teacher.lecture_groups')}}">Lectures & Groups</a></p>
        </div>
        <div class="{{ $focus=='attendance' ? 'bg-blue-600 text-white px-3' : 'text-black' }} flex flex-row h-12 items-center space-x-4 text-sm mx-4 lg:text-base rounded drop-shadow">
            <i class="fa-solid fa-clipboard-user"></i>
            <p class=""><a href="{{route('teacher.attendance')}}">Attendance</a></p>
        </div>
    </div>
</div>

<div id="mobile-toggle" class="fixed md:hidden text-white left-3 z-50 w-14 h-10 top-3 rounded-md flex justify-center shadow-md items-end bg-blue-500 p-2 toggle-button">
    @switch($focus)
    @case('dashboard')
    <i class="fa-solid fa-house"></i>
    @break
    @case('lecture_group')
    <i class="fa-solid fa-book"></i>
    @break
    @case('attendance')
    <i class="fa-solid fa-clipboard-user"></i>
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