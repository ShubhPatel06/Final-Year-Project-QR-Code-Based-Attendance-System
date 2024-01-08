@extends("layouts.adminLayout")
@section('sidebar')
<x-admin-sidebar focus='attendance' />
@endsection
@section('content')

<div id="contentContainer" class="p-5 md:px-20 gap-y-20 mt-8 shadow-md">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl ">Attendance Details</h1>
    </div>

    <table class="min-w-fug-white shadow-md rounded-lg attendance-table" id="attendance-table">
        <thead>
            <tr class="bg-slate-200">
                <th class="p-4 font-semibold text-gray-700">Lecture Code</th>
                <th class="p-4 font-semibold text-gray-700">Lecture</th>
                <th class="p-4 font-semibold text-gray-700">Group</th>
                <th class="p-4 font-semibold text-gray-700">Date</th>
                <th class="p-4 font-semibold text-gray-700">Start Time</th>
                <th class="p-4 font-semibold text-gray-700">End Time</th>
                <th class="p-4 font-semibold text-gray-700">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white">
        </tbody>
    </table>

</div>

<script type="text/javascript">
    $(function() {
        var table = $('.attendance-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.attendance') }}",
            columns: [{
                    data: 'lecture.lecture_code',
                    name: 'lecture.lecture_code',
                    class: "p-4"
                },
                {
                    data: 'lecture.lecture_name',
                    name: 'lecture.lecture_name',
                    class: "p-4"
                },
                {
                    data: 'group.group_name',
                    name: 'group.group_name',
                    class: "p-4",
                    render: function(data, type, row, meta) {
                        return data + ' (' + row.group.division.division_name + ')';
                    }
                },
                {
                    data: 'date',
                    name: 'date',
                    class: "p-4"
                },
                {
                    data: 'start_time',
                    name: 'start_time',
                    class: "p-4"
                },
                {
                    data: 'end_time',
                    name: 'end_time',
                    class: "p-4"
                },
                {
                    data: 'action',
                    name: 'action',
                    class: "p-4",
                    orderable: false,
                    searchable: false
                }
            ]
        });


    });
</script>

@endsection