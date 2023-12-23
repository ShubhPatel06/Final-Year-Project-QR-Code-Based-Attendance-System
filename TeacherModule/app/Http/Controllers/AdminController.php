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
use App\Models\Student;
use App\Models\StudentGroups;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
        try {
            $validator = Validator::make($request->all(), [
                'role_type' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            Roles::updateOrCreate(
                [
                    'role_id' => $request->role_id
                ],
                [
                    'role_type' => $request->role_type,
                ]
            );

            return response()->json(['success' => 'Role saved successfully.']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            // Handle other exceptions as needed
            return response()->json(['error' => 'An error occurred while saving the role. Please try again.'], 500);
        }
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
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $request->user_id . ',user_id'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user_id . ',user_id'],
                'phoneNo' => ['required', 'string', 'max:255'],
                'role_id' => ['required', 'integer', 'in:1,2,3'],
                'password' => ['required', 'string', 'min:6'],
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

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

            return response()->json(['data' => [], 'message' => 'User saved successfully.']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            // Handle other exceptions as needed
            return response()->json(['error' => 'An error occurred while saving the user. Please try again.'], 500);
        }
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
        try {
            $validator = Validator::make($request->all(), [
                'faculty_name' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            Faculty::updateOrCreate(
                [
                    'faculty_id' => $request->faculty_id
                ],
                [
                    'faculty_name' => $request->faculty_name,
                ]
            );

            return response()->json(['data' => [], 'message' => 'Faculty saved successfully.']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while saving the faculty. Please try again.'], 500);
        }
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
        try {
            $validator = Validator::make($request->all(), [
                'course_code' => 'required|string|max:255',
                'course_name' => 'required|string|max:255',
                'faculty_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

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

            return response()->json(['data' => [], 'message' => 'Course saved successfully.']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while saving the course. Please try again.'], 500);
        }
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
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'faculty_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

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
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while saving the lecturer.'], 500);
        }
    }

    public function getLecturerByID($id)
    {
        $data = Lecturers::with(['user', 'faculty'])
            ->find($id);
        return response()->json($data);
    }

    public function editLecturer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'phoneNo' => 'required|string|max:20',
                'editfaculty_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $user_id = $request->input('user_id');
            $first_name = $request->input('first_name');
            $last_name = $request->input('last_name');
            $username = $request->input('username');
            $email = $request->input('email');
            $phoneNo = $request->input('phoneNo');
            $faculty_id = $request->input('editfaculty_id');

            // Start a database transaction
            DB::beginTransaction();

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
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            // If an error occurs, roll back the transaction
            DB::rollBack();

            return response()->json(['error' => 'An error occurred while saving the lecturer.'], 500);
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
        try {
            $validator = Validator::make($request->all(), [
                'lecture_code' => 'required|string|max:255',
                'lecture_name' => 'required|string|max:255',
                'course_id' => 'required|integer',
                'lecturer_id' => 'required|integer',
                'total_hours' => 'required|integer',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

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
                ]
            );

            return response()->json(['success' => 'Lecture saved successfully.']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while saving the lecture.'], 500);
        }
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
        try {
            $validator = Validator::make($request->all(), [
                'group_name' => 'required|string|max:255',
                'year' => 'required|integer',
                'semester' => 'required|integer',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

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
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while saving the group.'], 500);
        }
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
        try {
            $validator = Validator::make($request->all(), [
                'lecture_id' => 'required|exists:lectures,lecture_id',
                'group_id' => 'required|exists:groups,group_id',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            LectureGroups::create([
                'lecture_id' => $request->lecture_id,
                'group_id' => $request->group_id,
            ]);

            return response()->json(['success' => 'Lecture Group saved successfully.']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while saving the lecture group.'], 500);
        }
    }

    public function deleteLectureGroup(Request $request)
    {
        LectureGroups::where([
            'lecture_id' => $request->lecture_id,
            'group_id' => $request->group_id,
        ])->delete();

        return response()->json(['success' => 'Lecture Group deleted successfully.']);
    }

    // STUDENTS

    public function getStudents(Request $request)
    {
        $courses = Courses::all();
        $users = User::where('role_id', 3)->get();
        $groups = Groups::all();


        if ($request->ajax()) {
            $data = Student::with(['user', 'course'])
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="flex gap-4 text-white font-semibold"><a href="javascript:void(0)" data-id="' . $row->user_id . '" data-adm="' . $row->adm_no . '" class="edit bg-emerald-500 hover:bg-emerald-600 font-medium rounded-lg text-sm px-5 py-2 text-center editStudent">Edit</a></div>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.students', ['courses' => $courses, 'users' => $users]);
    }

    public function storeStudent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,user_id',
                'course_id' => 'required|exists:courses,course_id',
                'year_of_study' => 'required|integer|min:1',
                'semester' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            Student::create([
                'user_id' => $request->user_id,
                'course_id' => $request->course_id,
                'year_of_study' => $request->year_of_study,
                'semester' => $request->semester,
            ]);

            return response()->json(['success' => 'Student saved successfully.']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while saving the student.'], 500);
        }
    }

    public function getStudentByID($id)
    {
        $data = Student::with(['user', 'course'])
            ->where('user_id', $id)
            ->first();

        if (!$data) {
            return response()->json(['error' => 'Student not found.']);
        }

        return response()->json($data);
    }

    public function editStudent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,user_id',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phoneNo' => 'required|string|max:20',
                'edit_course_id' => 'required|exists:courses,course_id',
                'edit_year_of_study' => 'required|integer|min:1',
                'edit_semester' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Start a database transaction
            DB::beginTransaction();

            // Update the users table
            User::where('user_id', $request->user_id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'username' => $request->username,
                'email' => $request->email,
                'phoneNo' => $request->phoneNo,
            ]);

            // Update the students table
            Student::where('user_id', $request->user_id)->update([
                'course_id' => $request->edit_course_id,
                'year_of_study' => $request->edit_year_of_study,
                'semester' => $request->edit_semester,
            ]);

            // Commit the transaction if all updates were successful
            DB::commit();

            return response()->json(['success' => 'Student saved successfully.']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            // If an error occurs, roll back the transaction
            DB::rollBack();

            return response()->json(['error' => 'An error occurred while saving the student.'], 500);
        }
    }

    // STUDENT GROUPS

    public function getStudentGroups(Request $request)
    {
        $students = Student::with(['user'])->get();
        $groups = Groups::all();

        if ($request->ajax()) {
            $studentGroups = StudentGroups::with(['student', 'student.user', 'group'])->get();

            return DataTables::of($studentGroups)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="flex gap-4 text-white font-semibold"><a href="javascript:void(0)" data-id="' . $row->group_id . '" data-del="' . $row->adm_no . '" class="delete bg-red-500 hover:bg-red-600 font-medium rounded-lg text-sm px-5 py-2 text-center deleteStudentGroup">Delete</a> ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.student_groups', ['students' => $students, 'groups' => $groups]);
    }

    public function storeStudentGroup(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'adm_no' => 'required|exists:students,adm_no',
                'group_id' => 'required|exists:groups,group_id',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            StudentGroups::create([
                'adm_no' => $request->adm_no,
                'group_id' => $request->group_id,
            ]);

            return response()->json(['success' => 'Student Group saved successfully.']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while saving the student group.'], 500);
        }
    }

    public function deleteStudentGroup(Request $request)
    {
        StudentGroups::where([
            'adm_no' => $request->adm_no,
            'group_id' => $request->group_id,
        ])->delete();

        return response()->json(['success' => 'Student Group deleted successfully.']);
    }
}
