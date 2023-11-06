@extends("layouts.adminLayout")
@section('sidebar')
<x-teacher-sidebar focus='dashboard' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">
    <h1 class="text-3xl mb-6">Profile Details</h1>
    <table class="min-w-full table-auto text-left border-collapse border-slate-900 bg-white shadow-md rounded-lg">
        <thead>
            <tr class="bg-slate-200">
                <th class="border border-slate-900 p-4 font-semibold text-gray-700">First Name</th>
                <th class="border border-slate-900 p-4 font-semibold text-gray-700">Last Name</th>
                <th class="border border-slate-900 p-4 font-semibold text-gray-700">Username</th>
                <th class="border border-slate-900 p-4 font-semibold text-gray-700">Email</th>
                <th class="border border-slate-900 p-4 font-semibold text-gray-700">Phone Number</th>
                <th class="border border-slate-900 p-4 font-semibold text-gray-700">Faculty</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border border-slate-900 p-4">{{ $teacher->first_name }}</td>
                <td class="border border-slate-900 p-4">{{ $teacher->last_name }}</td>
                <td class="border border-slate-900 p-4">{{ $teacher->username }}</td>
                <td class="border border-slate-900 p-4">{{ $teacher->email }}</td>
                <td class="border border-slate-900 p-4">{{ $teacher->phoneNo }}</td>
                <td class="border border-slate-900 p-4"> {{ $facultyName }}</td>
            </tr>
        </tbody>
    </table>

</div>
@endsection