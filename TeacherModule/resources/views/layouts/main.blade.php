<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Module</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
</head>

<body>
    @php
    $user = Auth::User();
    @endphp
    <nav class="fixed flex top-0 w-full h-16 z-50 border-b-2 justify-between items-center px-11">
        <div class="text-black md:flex flex-col justify-center pl-3">
            <P class="text-xl text-white">Attendance System</P>
        </div>

        <div class="flex items-center gap-x-3 h-full">
            <span class="text-black">{{$user->first_name. " ".$user->last_name}}</span>
            <div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="ml-3 text-red-500 hover:underline"> Logout </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- @yield('layoutContent') -->
    <div class="mt-16">
        @yield('layoutContent')
    </div>
</body>

</html>