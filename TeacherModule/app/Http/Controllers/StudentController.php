<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Groups;
use App\Models\LectureGroups;
use App\Models\Lectures;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentLectureGroups;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        if ($student) {
            $courseName = $student->student->course->course_name;
            $admNo = $student->student->adm_no;
            return view('student.index', ['student' => $student, 'admNo' => $admNo, 'courseName' => $courseName]);
        }
    }

    public function getStudentLectures(Request $request)
    {
        $user = Auth::user();

        $courseID = Student::where('adm_no', $user->student->adm_no)->value('course_id');
        $courseLectures = Lectures::where('course_id', $courseID)->get();
        $admNo = $user->student->adm_no;

        if ($request->ajax()) {
            $lectures = StudentLectureGroups::with('lecture', 'group', 'group.division')->where('adm_no', $user->student->adm_no)
                ->get();


            // dd($lectures);
            return DataTables::of($lectures)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="flex gap-4 text-white font-semibold"><a href="' . route('student.attendance', ['groupID' => $row->group_id, 'lectureID' => $row->lecture_id]) . '"  data-id="' . $row->lecture_id . '" class="edit bg-emerald-500 hover:bg-emerald-600 font-medium rounded-lg text-sm px-5 py-2 text-center viewAttendance">View Attendance</a></div>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('student.lectures', ['courseLectures' => $courseLectures, 'admNo' => $admNo]);
    }

    public function getGroupsByLecture($id)
    {
        $groups = LectureGroups::with('group', 'group.division')->where('lecture_id', $id)->get();

        return response()->json($groups);
    }

    public function registerLecture(Request $request)
    {
        $user = Auth::user();
        $admNo = $user->student->adm_no;

        try {
            $validator = Validator::make($request->all(), [
                'lecture_id' => 'required|exists:lectures,lecture_id',
                'group_id' => 'required|exists:semester_groups,group_id',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Check if the user is already registered for the given lecture
            $existingRegistration = StudentLectureGroups::where('adm_no', $admNo)
                ->where('lecture_id', $request->lecture_id)
                ->exists();

            if ($existingRegistration) {
                throw new ValidationException($validator->addError('lecture_id', 'You are already registered for this lecture.'));
            }

            StudentLectureGroups::create([
                'adm_no' => $admNo,
                'lecture_id' => $request->lecture_id,
                'group_id' => $request->group_id,
            ]);

            return response()->json(['success' => 'Lecture registered successfully.']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while registering for the lecture.'], 500);
        }
    }

    public function getStudentAttendance(Request $request)
    {
        $user = Auth::user();
        $admNo = $user->student->adm_no;

        $lectureID = $request->route()->parameter('lectureID');
        $groupID = $request->route()->parameter('groupID');

        $lecture = Lectures::where('lecture_id', $lectureID)->first();
        $group = Groups::with('division')->where('group_id', $groupID)->first();

        $presentHours = 0;
        $absentHours = 0;
        $totalHours = 0;
        $absentPercent = 0.0;

        $recordIDs = AttendanceRecord::where('lecture_id', $lectureID)
            ->where('group_id', $groupID)
            ->pluck('record_id');

        $allStudentAttendanceData = [];

        foreach ($recordIDs as $recordID) {
            $studentAttendanceData = StudentAttendance::with('attendanceRecord')
                ->where('attendance_record_id', $recordID)
                ->where('student_adm_no', $admNo)
                ->get();

            $allStudentAttendanceData[] = $studentAttendanceData;
        }

        $flattenedData = [];
        foreach ($allStudentAttendanceData as $collection) {
            foreach ($collection as $studentAttendance) {
                $totalHours += $studentAttendance->hours;

                if ($studentAttendance->is_present == 1) {
                    $presentHours += $studentAttendance->hours;
                } elseif ($studentAttendance->is_present == 2) {
                    $absentHours += $studentAttendance->hours;
                }

                $flattenedData[] = [
                    'date' => $studentAttendance->attendanceRecord->date,
                    'start_time' => $studentAttendance->attendanceRecord->start_time,
                    'end_time' => $studentAttendance->attendanceRecord->end_time,
                    'hours' => $studentAttendance->hours,
                    'is_present' => $studentAttendance->is_present,
                ];
            }
        }

        $absentPercent = ($totalHours > 0) ? ($absentHours / $totalHours) * 100 : 0;
        $absentPercent = number_format($absentPercent, 2);

        if ($request->ajax()) {

            return DataTables::of($flattenedData)
                ->addIndexColumn()
                ->make(true);
        }

        // return view('student.attendance', ['lecture' => $lecture, 'group' => $group]);
        return view('student.attendance', [
            'lecture' => $lecture,
            'group' => $group,
            'presentHours' => $presentHours,
            'absentHours' => $absentHours,
            'absentPercent' => $absentPercent,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'admission_number' => 'required',
            'password' => 'required',
        ]);

        // Find the user_id in the students_table
        $student = Student::with('course')->where('adm_no', $request->input('admission_number'))->first();

        if (!$student) {
            return response()->json(['error' => 'Student Not Registered'], 401);
        }

        // Get the corresponding user from the users table
        $user = User::find($student->user_id);

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('token-name')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user, 'admission_number' => $student->adm_no, 'course' => $student->course], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function updateAttendance(Request $request)
    {
        $this->validate($request, [
            'admission_number' => 'required|integer',
            'record_id' => 'required|integer',
            'verification_token' => 'required|string',
        ]);

        // Validate verification token
        $record = AttendanceRecord::findOrFail($request->input('record_id'));

        if ($record->verification_token !== $request->input('verification_token')) {
            return response()->json(['error' => 'Invalid verification token'], 401);
        }

        $publicIpAddress = Http::get('https://api64.ipify.org?format=json')->json()['ip'];
        $studentIP = $request->input('ipv4');

        if ($studentIP && $studentIP !== $publicIpAddress) {
            return response()->json(['error' => 'Cannot Update Attendance!You are not on the same network.'], 401);
        }

        // Update student attendance
        StudentAttendance::updateOrCreate(
            [
                'attendance_record_id' => $request->input('record_id'),
                'student_adm_no' => $request->input('admission_number'),
            ],
            ['is_present' => 1]
        );

        return response()->json(['message' => 'Attendance updated successfully']);
    }

    public function getGroups(Request $request, $admNo)
    {
        $data = StudentLectureGroups::with('group')
            ->where('adm_no', $admNo)
            ->select('group_id')
            ->distinct()
            ->get();

        return response()->json(['groups' => $data]);
    }

    public function getLectures(Request $request)
    {
        $admNo = $request->route()->parameter('admNo');
        $groupID = $request->route()->parameter('groupID');

        $data = StudentLectureGroups::with('group', 'lecture', 'lecture.lecturerAllocation.lecturer.user')
            ->where('adm_no', $admNo)
            ->where('group_id', $groupID)
            ->get();

        return response()->json(['data' => $data]);
    }

    public function getAttendanceRecords(Request $request)
    {
        $admNo = $request->route()->parameter('admNo');
        $lectureID = $request->route()->parameter('lectureID');
        $groupID = $request->route()->parameter('groupID');

        $attendanceRecords = AttendanceRecord::with('lecture')->where('lecture_id', $lectureID)
            ->where('group_id', $groupID)
            ->get();

        $presentHours = 0;
        $absentHours = 0;
        $totalHours = 0;
        $absentPercent = 0;

        // Filter student attendance based on admission number
        $filteredAttendance = $attendanceRecords->flatMap(function ($record) use ($admNo, &$presentHours, &$absentHours, &$totalHours, &$absentPercent) {
            $attendanceList = $record->studentAttendance->where('student_adm_no', $admNo)->all();

            // Calculate total hours only for records where is_present is equal to 1
            $presentHours += collect($attendanceList)->where('is_present', 1)->sum('hours');
            $absentHours += collect($attendanceList)->where('is_present', 2)->sum('hours');
            $totalHours += collect($attendanceList)->sum('hours');

            if ($totalHours > 0) {
                $absentPercent = number_format(($absentHours / $totalHours) * 100, 2);
                $absentPercent = (float) $absentPercent;
            } else {
                $absentPercent = 0.0; // Set absentPercent to 0 if totalHours is zero
            }

            return [
                'record' => $record,
                'attendance' => $attendanceList,
            ];
        });

        return response()->json(['presentHours' => $presentHours, 'absentHours' => $absentHours, 'absentPercent' => $absentPercent, 'AttendanceRecord' => $attendanceRecords, 'filteredAttendance' => $filteredAttendance]);
    }
}
