<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentLectureGroups;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class StudentController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'admission_number' => 'required',
            'password' => 'required',
        ]);

        // Find the user_id in the students_table
        $student = Student::where('adm_no', $request->input('admission_number'))->first();

        if (!$student) {
            return response()->json(['error' => 'Student Not Registered'], 401);
        }

        // Get the corresponding user from the users table
        $user = User::find($student->user_id);

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('token-name')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user, 'admission_number' => $student->adm_no], 200);
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

        // // Filter student attendance based on admission number
        // $filteredAttendance = $attendanceRecords->flatMap(function ($record) use ($admNo) {
        //     return $record->studentAttendance->where('student_adm_no', $admNo)->all();
        // });

        $totalHours = 0;

        // Filter student attendance based on admission number
        $filteredAttendance = $attendanceRecords->flatMap(function ($record) use ($admNo, &$totalHours) {
            $attendanceList = $record->studentAttendance->where('student_adm_no', $admNo)->all();

            // Calculate total hours only for records where is_present is equal to 1
            $totalHours += collect($attendanceList)->where('is_present', 1)->sum('hours');

            return [
                'record' => $record,
                'attendance' => $attendanceList,
            ];
        });

        return response()->json(['totalHours' => $totalHours, 'AttendanceRecord' => $attendanceRecords, 'filteredAttendance' => $filteredAttendance]);
    }
}
