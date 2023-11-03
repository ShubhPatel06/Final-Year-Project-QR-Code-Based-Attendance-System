<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Courses;
use App\Models\Faculty;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;


class AdminController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        return view('admin.index', ['admin' => $admin]);
    }

    // ROLES

    public function getRoles(Request $request)
    {

        if ($request->ajax()) {
            $data = Roles::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="flex gap-4 text-white font-semibold"><a href="javascript:void(0)" data-id="' . $row->role_id . '" class="edit bg-emerald-500 hover:bg-emerald-600 font-medium rounded-lg text-sm px-5 py-2 text-center editRole">Edit</a> ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.roles');
    }

    public function storeRole(Request $request)
    {
        Roles::updateOrCreate(
            [
                'role_id' => $request->role_id
            ],
            [
                'role_type' => $request->role_type,

            ]
        );

        return response()->json(['success' => 'Role saved successfully.']);
    }

    public function editRole($id)
    {
        $role = Roles::find($id);
        return response()->json($role);
    }

    public function deleteRole($id)
    {
        Roles::find($id)->delete();

        return response()->json(['success' => 'Role deleted successfully.']);
    }

    // FACULTY

    public function getFaculties(Request $request)
    {

        if ($request->ajax()) {
            $data = Faculty::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="flex gap-4 text-white font-semibold"><a href="javascript:void(0)" data-id="' . $row->faculty_id . '" class="edit bg-emerald-500 hover:bg-emerald-600 font-medium rounded-lg text-sm px-5 py-2 text-center editFaculty">Edit</a> ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.faculties');
    }

    public function storeFaculty(Request $request)
    {
        Faculty::updateOrCreate(
            [
                'faculty_id' => $request->faculty_id
            ],
            [
                'faculty_name' => $request->faculty_name,

            ]
        );
        return response()->json(['success' => 'Faculty saved successfully.']);
    }

    public function editFaculty($id)
    {
        $faculty = Faculty::find($id);
        return response()->json($faculty);
    }

    public function deleteFaculty($id)
    {
        Faculty::find($id)->delete();

        return response()->json(['success' => 'Faculty deleted successfully.']);
    }

    // COURSES
    public function getCourses(Request $request)
    {
        $faculties = Faculty::all();

        if ($request->ajax()) {
            // $data = Courses::all();
            $data = Courses::select('courses.*', 'faculty.faculty_name')
                ->join('faculty', 'courses.faculty_id', '=', 'faculty.faculty_id')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="flex gap-4 text-white font-semibold"><a href="javascript:void(0)" data-id="' . $row->course_id . '" class="edit bg-emerald-500 hover:bg-emerald-600 font-medium rounded-lg text-sm px-5 py-2 text-center editCourse">Edit</a></div>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.courses', ['faculties' => $faculties]);
    }

    public function storeCourse(Request $request)
    {
        Courses::updateOrCreate(
            [
                'course_id' => $request->course_id
            ],
            [
                'course_code' => $request->course_code,
                'course_name' => $request->course_name,
                'faculty_id' => $request->faculty_id,

            ]
        );
        return response()->json(['success' => 'Course saved successfully.']);
    }

    public function editCourse($id)
    {
        $course = Courses::find($id);
        return response()->json($course);
    }
}
