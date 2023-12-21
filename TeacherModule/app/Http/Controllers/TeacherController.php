<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Groups;
use App\Models\LectureGroups;
use App\Models\Lectures;
use App\Models\Student;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

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

        $groupDetails = Groups::find($groupID);
        $groupName = $groupDetails ? $groupDetails->group_name : '';
        return view('teacher.groupStudents', compact('groupName'));
    }

    public function attendanceIndex(Request $request)
    {
        $teacher = Auth::user();

        if ($request->ajax()) {
            $data = AttendanceRecord::with(['lecture', 'group'])
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // $actionBtn = '<div class="flex gap-4 text-white font-semibold"><a href="javascript:void(0)" data-id="' . $row->record_id . '" class="edit bg-emerald-500 hover:bg-emerald-600 font-medium rounded-lg text-sm px-5 py-2 text-center viewStudentRecords">Student Records</a></div>';
                    // return $actionBtn;

                    $actionBtn = '<div class="flex gap-4 text-white font-semibold">';

                    $actionBtn .= '<a href="' . route('teacher.student_records', ['recordID' => $row->record_id]) . '" data-id="' . $row->record_id . '" class="edit bg-emerald-500 hover:bg-emerald-600 font-medium rounded-lg text-sm px-5 py-2 text-center viewStudentRecords">Student Records</a>';

                    // Display QR Code link if qr_code_path is not null
                    if ($row->qr_code_path !== null) {
                        $actionBtn .= '<button data-qr-code="' . $row->qr_code_path . '" data-id="' . $row->record_id . '" class="bg-blue-500 hover:bg-blue-600 font-medium rounded-lg text-sm px-5 py-2 text-center displayQR">Display QR Code</button>';
                    }

                    $actionBtn .= '</div>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $lectures = Lectures::with(['course'])
            ->where('lecturer_id', $teacher->user_id)
            ->get();
        return view('teacher.attendance', ['lectures' => $lectures]);
    }

    public function getGroups($lectureId)
    {
        $groupsData = LectureGroups::with(['group'])
            ->where('lecture_id', $lectureId)
            ->get();

        $groups = $groupsData->map(function ($lectureGroup) {
            return [
                'group_id' => $lectureGroup->group->group_id,
                'group_name' => $lectureGroup->group->group_name,
            ];
        });

        return response()->json($groups);
    }

    public function storeAttendanceData(Request $request)
    {
        $request->validate([
            'lecture_id' => 'required',
            'group_id' => 'required',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Generate a verification token
        $verificationToken = Str::random(20);

        $attendanceRecord = AttendanceRecord::create([
            'lecture_id' => $request->input('lecture_id'),
            'group_id' => $request->input('group_id'),
            'date' => $request->input('date'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'verification_token' => $verificationToken,
        ]);

        $lectureName = Lectures::where('lecture_id', $request->input('lecture_id'))->value('lecture_name');
        $groupName = Groups::where('group_id', $request->input('group_id'))->value('group_name');

        $dataArray = [
            'Lecture' => $lectureName,
            'Group' => $groupName,
            'Date' => $request->input('date'),
            'Start Time' => $request->input('start_time'),
            'End Time' => $request->input('end_time'),
            'Record ID' => $attendanceRecord->record_id,
            'Verification Token' => $verificationToken,
        ];

        // Get Public IP address
        if ($request->input('session_type') === 'physical') {
            $publicIpAddress = Http::get('https://api64.ipify.org?format=json')->json()['ip'];
            $dataArray['IP Address'] = $publicIpAddress;
        }

        $qrCodeData = json_encode($dataArray);

        // Generate QR code and store it
        $qrCodePath = 'qrcodes/' . uniqid('qrcode_') . '.svg';
        QrCode::format('svg') // Set the format to PNG
            ->size(420)
            ->generate($qrCodeData, public_path($qrCodePath));

        // Update the attendance record with the QR code path
        $attendanceRecord->update(['qr_code_path' => $qrCodePath]);

        // Get students based on the group_id
        $students = Student::where('group_id', $request->input('group_id'))->get();

        // Create student attendance records
        foreach ($students as $student) {
            StudentAttendance::create([
                'attendance_record_id' => $attendanceRecord->record_id,
                'student_adm_no' => $student->adm_no,
            ]);
        }

        return response()->json(['success' => 'Record saved successfully.']);
    }

    public function deleteQRCode($record_id)
    {
        $qrCode = AttendanceRecord::where('record_id', $record_id)->first();

        if (!$qrCode) {
            return response()->json(['error' => 'QR code not found'], 404);
        }

        // Get the QR code path from the database
        $qrCodePath = $qrCode->qr_code_path;

        $qrCode->qr_code_path = null;

        $qrCode->verification_token = null;
        $qrCode->save();

        // Delete the QR code file from the public directory
        if (File::exists(public_path($qrCodePath))) {
            File::delete(public_path($qrCodePath));
        }

        return response()->json(['success' => true]);
    }

    public function getStudentRecords(Request $request, $recordID)
    {
        if ($request->ajax()) {
            $data = StudentAttendance::with(['student.user'])
                ->where('attendance_record_id', $recordID)
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="flex gap-4 text-white font-semibold"><a href="javascript:void(0)" data-id="' . $row->attendance_id . '" class="edit bg-emerald-500 hover:bg-emerald-600 font-medium rounded-lg text-sm px-5 py-2 text-center updateAttendance">Update Attendance</a></div>';
                    return $actionBtn;
                })
                ->make(true);
        }

        return view('teacher.student_records');
    }

    public function getAttendanceByID($id)
    {
        $data = StudentAttendance::where('attendance_id', $id)
            ->first();

        if (!$data) {
            return response()->json(['error' => 'Record not found.']);
        }

        return response()->json($data);
    }

    public function editAttendance(Request $request)
    {
        $attendance_id = $request->input('attendance_id');
        $is_present = $request->input('is_present');

        StudentAttendance::where('attendance_id', $attendance_id)->update([
            'is_present' => $is_present,
        ]);

        return response()->json(['success' => 'Attendance updated successfully.']);
    }
}
