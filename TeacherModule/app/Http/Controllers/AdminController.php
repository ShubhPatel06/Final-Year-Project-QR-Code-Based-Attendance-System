<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Courses;
use App\Models\Faculty;
use App\Models\Groups;
use App\Models\LectureGroups;
use App\Models\Lecturers;
use App\Models\Lectures;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
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

    // USERS
    public function getUsers(Request $request)
    {
        $roles = Roles::all();

        if ($request->ajax()) {
            $data = User::with('role')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="flex gap-4 text-white font-semibold"><a href="javascript:void(0)" data-id="' . $row->user_id . '" class="edit bg-emerald-500 hover:bg-emerald-600 font-medium rounded-lg text-sm px-5 py-2 text-center editUser">Edit</a> ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.users', ['roles' => $roles]);
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $request->user_id . ',user_id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user_id . ',user_id'],
            'phoneNo' => ['required', 'string', 'max:255'],
            'role_id' => ['required', 'integer', 'in:1,2,3'],
        ]);

        $userData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'phoneNo' => $request->phoneNo,
            'role_id' => $request->role_id,
        ];

        if (!empty($request->password)) {
            $userData['password'] = Hash::make($request->password);
        }

        User::updateOrCreate(
            ['user_id' => $request->user_id],
            $userData
        );

        return response()->json(['success' => 'User saved successfully.']);
    }


    public function editUser($id)
    {
        $user = User::find($id);
        return response()->json($user);
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

    // LECTURERS 

    public function getLecturers(Request $request)
    {
        $faculties = Faculty::all();
        $users = User::where('role_id', 2)->get();

        if ($request->ajax()) {
            $data = Lecturers::with(['user', 'faculty'])
                ->get();


            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="flex gap-4 text-white font-semibold"><a href="javascript:void(0)" data-id="' . $row->user_id . '" class="edit bg-emerald-500 hover:bg-emerald-600 font-medium rounded-lg text-sm px-5 py-2 text-center editLecturer">Edit</a></div>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.lecturers', ['faculties' => $faculties, 'users' => $users]);
    }

    public function storeLecturer(Request $request)
    {
        Lecturers::updateOrCreate(
            [
                'user_id' => $request->user_id
            ],
            [
                'user_id' => $request->user_id,
                'faculty_id' => $request->faculty_id,

            ]
        );
        return response()->json(['success' => 'Lecturer saved successfully.']);
    }

    public function getLecturerByID($id)
    {
        $data = Lecturers::with(['user', 'faculty'])
            ->find($id);
        return response()->json($data);
    }

    public function editLecturer(Request $request)
    {
        $user_id = $request->input('user_id');
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $username = $request->input('username');
        $email = $request->input('email');
        $phoneNo = $request->input('phoneNo');
        $faculty_id = $request->input('editfaculty_id');

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Update the users table
            User::where('user_id', $user_id)->update([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'username' => $username,
                'email' => $email,
                'phoneNo' => $phoneNo,
            ]);

            // Update the lecturers table
            Lecturers::where('user_id', $user_id)->update(['faculty_id' => $faculty_id]);

            // Commit the transaction if all updates were successful
            DB::commit();

            return response()->json(['success' => 'Lecturer saved successfully.']);
        } catch (\Exception $e) {
            // If an error occurs, roll back the transaction
            DB::rollBack();

            return response()->json(['error' => 'An error occurred while saving the lecturer.']);
        }
    }

    // LECTURES
    public function getLectures(Request $request)
    {
        $courses = Courses::all();
        $lecturers = Lecturers::with('user')->get();

        if ($request->ajax()) {
            $data = Lectures::with(['course', 'lecturer.user'])
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="flex gap-4 text-white font-semibold"><a href="javascript:void(0)" data-id="' . $row->lecture_id . '" class="edit bg-emerald-500 hover:bg-emerald-600 font-medium rounded-lg text-sm px-5 py-2 text-center editLecture">Edit</a></div>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.lectures', ['courses' => $courses, 'lecturers' => $lecturers]);
    }

    public function storeLecture(Request $request)
    {
        Lectures::updateOrCreate(
            [
                'lecture_id' => $request->lecture_id
            ],
            [
                'lecture_code' => $request->lecture_code,
                'lecture_name' => $request->lecture_name,
                'course_id' => $request->course_id,
                'lecturer_id' => $request->lecturer_id,
                'total_hours' => $request->total_hours,
                'day' => $request->day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,

            ]
        );
        return response()->json(['success' => 'Lecture saved successfully.']);
    }

    public function editLecture($id)
    {
        $lecture = Lectures::with(['course', 'lecturer.user'])
            ->find($id);
        return response()->json($lecture);
    }

    // GROUPS
    public function getGroups(Request $request)
    {

        if ($request->ajax()) {
            $data = Groups::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="flex gap-4 text-white font-semibold"><a href="javascript:void(0)" data-id="' . $row->group_id . '" class="edit bg-emerald-500 hover:bg-emerald-600 font-medium rounded-lg text-sm px-5 py-2 text-center editGroup">Edit</a> ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.groups');
    }

    public function storeGroup(Request $request)
    {
        Groups::updateOrCreate(
            [
                'group_id' => $request->group_id
            ],
            [
                'group_name' => $request->group_name,
                'year' => $request->year,
                'semester' => $request->semester,

            ]
        );

        return response()->json(['success' => 'Group saved successfully.']);
    }

    public function editGroup($id)
    {
        $group = Groups::find($id);
        return response()->json($group);
    }

    // LECTURE GROUPS
    public function getLectureGroups(Request $request)
    {
        $lectures = Lectures::all();
        $groups = Groups::all();

        if ($request->ajax()) {
            $lectureGroups = LectureGroups::with(['lecture', 'group'])->get();
            return DataTables::of($lectureGroups)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="flex gap-4 text-white font-semibold"><a href="javascript:void(0)" data-id="' . $row->group_id . '" data-del="' . $row->lecture_id . '" class="delete bg-red-500 hover:bg-red-600 font-medium rounded-lg text-sm px-5 py-2 text-center deleteLectureGroup">Delete</a> ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.lecture_groups', ['lectures' => $lectures, 'groups' => $groups]);
    }

    public function storeLectureGroup(Request $request)
    {
        LectureGroups::create([
            'lecture_id' => $request->lecture_id,
            'group_id' => $request->group_id,
        ]);

        return response()->json(['success' => 'Lecture Group saved successfully.']);
    }

    public function deleteLectureGroup(Request $request)
    {
        LectureGroups::where([
            'lecture_id' => $request->lecture_id,
            'group_id' => $request->group_id,
        ])->delete();

        return response()->json(['success' => 'Lecture Group deleted successfully.']);
    }
}
