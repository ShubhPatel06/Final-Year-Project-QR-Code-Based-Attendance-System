<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Groups;
use App\Models\LectureGroups;
use App\Models\Lectures;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

    public function attendanceIndex()
    {
        $teacher = Auth::user();
        if ($teacher) {
            $lectures = Lectures::with(['course'])
                ->where('lecturer_id', $teacher->user_id)
                ->get();
            return view('teacher.attendance', ['lectures' => $lectures]);
        }
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

        AttendanceRecord::create([
            'lecture_id' => $request->input('lecture_id'),
            'group_id' => $request->input('group_id'),
            'date' => $request->input('date'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
        ]);

        $qrCodeData = "Lecture: " . $request->input('lecture_id') . "\n"
            . "Group: " . $request->input('group_id') . "\n"
            . "Date: " . $request->input('date') . "\n"
            . "Start Time: " . $request->input('start_time') . "\n"
            . "End Time: " . $request->input('end_time');

        // Generate QR code and store it
        $qrCodePath = 'qrcodes/' . uniqid('qrcode_') . '.png';
        QrCode::size(300)
            ->generate($qrCodeData, public_path($qrCodePath));

        return response()->json(['success' => 'Record saved successfully.']);
    }
}
