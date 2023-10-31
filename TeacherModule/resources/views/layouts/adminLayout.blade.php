@extends('layouts.main')
@section('layoutContent')
<div class="flex w-[100vw]">

    <div class="md:w-[20%]">
        @yield('sidebar')
    </div>


    <div class="md:w-[90%] w-full pt-20 bg-slate-50 md:pt-0 m-4">
        @yield('content')
    </div>

</div>

@endsection