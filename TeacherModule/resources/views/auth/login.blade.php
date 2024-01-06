@extends('layouts.auth')

@section('content')
<section class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full sm:w-[28rem] md:w-[28rem] bg-white shadow-xl rounded-md mx-4 sm:mx-auto">
        <div class="text-center p-8">
            <h1 class="text-4xl text-blue-500 font-bold">Attendance System</h1>
            <h2 class="text-2xl mt-3 font-semibold text-gray-700">Login to your account</h2>
        </div>
        @if(session('error'))
        <p class="text-red-500 text-center p-4">{{ session('error') }}</p>
        @endif
        <form action="{{ route('login.post') }}" method="POST" class="p-8 pt-0">
            @csrf
            <div class="my-4">
                <label for="username" class="text-gray-700 font-semibold">Username</label>
                <input type="text" id="username" name="username" autocomplete="off" class="rounded-lg mt-1 w-full px-3 py-2 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500" />
                @error('username')
                <span class="text-red-500 text-sm" role="alert">
                    {{ $message }}
                </span>
                @enderror
            </div>
            <div class="my-4">
                <label for="password" class="text-gray-700 font-semibold">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" autocomplete="off" class="rounded-lg mt-1 w-full px-3 py-2 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500" />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button type="button" onclick="togglePasswordVisibility()" class="text-gray-400 focus:outline-none">
                            <i id="passwordVisibilityIcon" class="far fa-eye text-gray-700"></i> <!-- Font Awesome eye icon -->
                        </button>
                    </div>
                </div>
                @error('password')
                <span class="text-red-500 text-sm" role="alert">
                    {{ $message }}
                </span>
                @enderror
            </div>

            <button type="submit" class="rounded-lg text-sm px-5 py-2.5 text-center w-full mt-6 border border-transparent font-semibold text-white bg-blue-500 hover:bg-blue-600">
                Login
            </button>
        </form>
    </div>
</section>

<script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById('password');
        var passwordIcon = document.getElementById('passwordVisibilityIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.className = 'far fa-eye-slash text-gray-700'; // Font Awesome eye-slash icon
        } else {
            passwordInput.type = 'password';
            passwordIcon.className = 'far fa-eye text-gray-700'; // Font Awesome eye icon
        }
    }
</script>
@endsection