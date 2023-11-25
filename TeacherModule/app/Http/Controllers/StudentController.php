<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Get the corresponding user from the users table
        $user = User::find($student->user_id);

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('token-name')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user], 200);
    }
}
