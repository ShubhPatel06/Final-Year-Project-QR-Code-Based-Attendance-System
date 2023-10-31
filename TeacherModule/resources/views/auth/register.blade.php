@extends('layouts.auth')
@section('content')
<section class="flex items-center justify-center min-h-screen">
    <div class="w-[28rem] sm:max-w-md md:max-w-lg border-2 shadow-lg shadow-blue-500/40 p-8 rounded-md mx-4 sm:mx-auto">
        <div class="mb-10">
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
                Sign Up Now
            </h2>
        </div>
        @if(Session::has('success'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">{{Session::get('success')}}</strong>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                </svg>
            </span>
        </div>
        @endif
        <form action="{{route('register.post')}}" method="POST">
            @csrf
            <div class="my-5">
                <label for="first_name" class="text-gray-700">
                    First Name
                </label>
                <input type="text" id="first_name" name="first_name" autocomplete="off" required class="rounded-md mt-1 appearance-none w-full px-3 py-2 border border-gray-400 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('first_name') is-invalid @enderror" />
                @error('first_name')
                <span class="text-red-500" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="my-5">
                <label for="last_name" class="text-gray-700">
                    Last Name
                </label>
                <input type="text" id="last_name" name="last_name" autocomplete="off" required class="rounded-md mt-1 appearance-none w-full px-3 py-2 border border-gray-400 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('last_name') is-invalid @enderror" />
                @error('last_name')
                <span class="text-red-500" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="my-5">
                <label for="username" class="text-gray-700">
                    Username
                </label>
                <input type="text" id="username" name="username" autocomplete="off" required class="rounded-md mt-1 appearance-none w-full px-3 py-2 border border-gray-400 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('username') is-invalid @enderror" />
                @error('name')
                <span class="text-red-500" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="my-5">
                <label for="email" class="text-gray-700">
                    Email
                </label>
                <input type="text" id="email" name="email" autocomplete="off" required class="rounded-md mt-1 appearance-none w-full px-3 py-2 border border-gray-400 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('email') is-invalid @enderror" />
                @error('email')
                <span class="text-red-500" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="my-5">
                <label for="email" class="text-gray-700">
                    Phone Number
                </label>
                <input type="text" id="phoneNo" name="phoneNo" autocomplete="off" required class="rounded-md mt-1 appearance-none w-full px-3 py-2 border border-gray-400 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('phoneNo') is-invalid @enderror" />
                @error('phoneNo')
                <span class="text-red-500" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="my-5">
                <label for="password" class="text-gray-700">
                    Password
                </label>
                <input type="password" id="password" name="password" autocomplete="off" required class="rounded-md mt-1 appearance-none w-full px-3 py-2 border border-gray-400 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('password') is-invalid @enderror" />
                @error('password')
                <span class="text-red-500" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="my-5">
                <label for="role_id" class="text-gray-700">
                    Role
                </label>
                <select id="role_id" name="role_id" class="rounded-md mt-1 appearance-none w-full px-3 py-2 border border-gray-400 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('role_Id') is-invalid @enderror" required>
                    <option value="1">Admin</option>
                    <option value="2">Teacher</option>
                </select>
                @error('password')
                <span class="text-red-500" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <button type="submit" class="w-full mt-6 py-3 px-4 border border-transparent text-sm font-semibold rounded-md text-white bg-blue-500 hover:bg-blue-600">
                Sign Up
            </button>
        </form>
    </div>
</section>