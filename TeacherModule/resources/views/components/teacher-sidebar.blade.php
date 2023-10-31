<div id="main-nav" class="pt-20 z-40 hidden w-[50%] top-0 left-0 md:flex md:w-[18%] fixed h-[100vh] bg-slate-500 border-r-2">
    <div class="flex flex-col gap-y-2 mt-2 items-center w-full text-sm my-8 px-8">

        <div class="{{ $focus=='dashboard' ? 'bg-blue-500 text-white px-6' : 'text-black' }} flex flex-row h-12 items-center space-x-4 text-sm mx-4 lg:text-base rounded drop-shadow">
            <p class=""><a href="">Dashboard</a></p>

        </div>

        <div class="{{ $focus=='classes' ? 'bg-blue-500 text-white px-6' : 'text-black' }} flex flex-row h-12 items-center space-x-4 text-sm mx-4 lg:text-base rounded drop-shadow">
            <p class=""><a href="">Classes</a></p>

        </div>

        <div class="{{ $focus=='attendance' ? 'bg-blue-500 text-white px-6' : 'text-black' }} flex flex-row h-12 items-center space-x-4 text-sm mx-4 lg:text-base rounded drop-shadow">
            <p class=""><a href="">Attendance</a></p>

        </div>

    </div>

</div>