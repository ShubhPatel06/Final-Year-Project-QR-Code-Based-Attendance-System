@extends("layouts.adminLayout")
@section('sidebar')
<x-admin-sidebar focus='dashboard' />
@endsection
@section('content')

<div id="contentContainer" class="px-5 md:px-20 gap-y-20 mt-8">
    <h1 class="text-3xl mb-6">Hello Admin</h1>
</div>
@endsection