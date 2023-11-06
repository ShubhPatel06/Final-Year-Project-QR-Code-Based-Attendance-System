<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lectures;
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
}
