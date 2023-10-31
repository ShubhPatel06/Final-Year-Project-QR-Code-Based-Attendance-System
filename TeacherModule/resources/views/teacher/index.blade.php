@extends("layouts.teacherLayout")
@section('sidebar')
<x-teacher-sidebar focus='dashboard' />
@endsection
@section('content')

<div id="contentContainer" class="px-5 md:px-20 gap-y-20 mt-8">
    <h1 class="text-3xl mb-6">Hello Teacher</h1>
</div>
@endsection