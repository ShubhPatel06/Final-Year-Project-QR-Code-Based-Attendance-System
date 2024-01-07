@extends("layouts.studentLayout")
@section('sidebar')
<x-student-sidebar focus='dashboard' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">
    <h1 class="text-3xl mb-6">Profile Details</h1>
    <table class="min-w-full table-auto text-left border-collapse border-slate-900 bg-white shadow-md rounded-lg">
        <thead>
            <tr class="bg-slate-200">
                <th class="border border-slate-900 p-4 font-semibold text-gray-700">Adm No</th>
                <th class="border border-slate-900 p-4 font-semibold text-gray-700">First Name</th>
                <th class="border border-slate-900 p-4 font-semibold text-gray-700">Last Name</th>
                <th class="border border-slate-900 p-4 font-semibold text-gray-700">Username</th>
                <th class="border border-slate-900 p-4 font-semibold text-gray-700">Email</th>
                <th class="border border-slate-900 p-4 font-semibold text-gray-700">Phone Number</th>
                <th class="border border-slate-900 p-4 font-semibold text-gray-700">Course</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border border-slate-900 p-4"> {{ $admNo }}</td>
                <td class="border border-slate-900 p-4">{{ $student->first_name }}</td>
                <td class="border border-slate-900 p-4">{{ $student->last_name }}</td>
                <td class="border border-slate-900 p-4">{{ $student->username }}</td>
                <td class="border border-slate-900 p-4">{{ $student->email }}</td>
                <td class="border border-slate-900 p-4">{{ $student->phoneNo }}</td>
                <td class="border border-slate-900 p-4"> {{ $courseName }}</td>
            </tr>
        </tbody>
    </table>

</div>
@endsection