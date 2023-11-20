<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Groups;
use App\Models\LectureGroups;
use App\Models\Lectures;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TeacherController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        if ($teacher) {
            $facultyName = $teacher->lecturer->faculty->faculty_name;
            return view('teacher.index', [
                'teacher' => $teacher,
                'facultyName' => $facultyName,
            ]);
        }
    }

    public function getLectures(Request $request)
    {
        $user = Auth::user();
        if ($request->ajax()) {
            $data = Lectures::with(['course'])
                ->where('lecturer_id', $user->user_id)
                ->get();

            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="flex gap-4 text-white font-semibold"><a href="javascript:void(0)" data-id="' . $row->lecture_id . '" class="edit bg-emerald-500 hover:bg-emerald-600 font-medium rounded-lg text-sm px-5 py-2 text-center viewGroups">View Groups</a></div>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('teacher.lectures');
    }

    public function getLectureGroups(Request $request)
    {
        $user = Auth::user();

        // if ($request->ajax()) {
        //     $data = Lectures::with(['groups'])->where('lecturer_id', $user->user_id)->get();

        //     // dd($data);
        //     return DataTables::of($data)
        //         ->addIndexColumn()
        //         ->addColumn('action', function ($row) {
        //             $actionBtn = '<div class="flex gap-4 text-white font-semibold"><a href="javascript:void(0)" data-id="' . $row->group_id . '" class="edit bg-emerald-500 hover:bg-emerald-600 font-medium rounded-lg text-sm px-5 py-2 text-center viewGroups">View Groups</a></div>';
        //             return $actionBtn;
        //         })
        //         ->addColumn('groups', function ($row) {
        //             // Access the groups and format them as needed
        //             $groups = $row->groups->map(function ($group) {
        //                 return $group->group_name . ' (' . $group->year . ', ' . $group->semester . ')';
        //             })->implode(', ');

        //             return $groups;
        //         })
        //         ->rawColumns(['action'])
        //         ->make(true);
        // }

        if ($request->ajax()) {
            $lectures = Lectures::with(['groups'])->where('lecturer_id', $user->user_id)->get();
            $data = [];

            foreach ($lectures as $lecture) {
                foreach ($lecture->groups as $group) {
                    $data[] = [
                        'lecture_name' => $lecture->lecture_name,
                        'group_id' => $group->group_id,
                        'group_name' => $group->group_name,
                        'year' => $group->year,
                        'semester' => $group->semester,
                        'action' => '<div class="flex gap-4 text-white font-semibold"><a href="' . route('teacher.group_students', ['groupID' => $group->group_id]) . '" data-id="' . $group->group_id . '" class="edit bg-emerald-500 hover:bg-emerald-600 font-medium rounded-lg text-sm px-5 py-2 text-center viewStudents">View Students</a></div>',
                    ];
                }
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return view('teacher.groups');
    }

    public function getGroupStudents(Request $request, $groupID)
    {
        if ($request->ajax()) {
            $data = Student::with(['user', 'course', 'group'])
                ->where('group_id', $groupID)
                ->get();

            $groupName = $data->isNotEmpty() ? $data[0]->group->group_name : '';

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        // Fetch the group details
        $groupDetails = Groups::find($groupID);
        $groupName = $groupDetails ? $groupDetails->group_name : '';
        return view('teacher.groupStudents', compact('groupName'));
    }
}
