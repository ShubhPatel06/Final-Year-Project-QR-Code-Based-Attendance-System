<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    use HasFactory;

    protected $table = 'student_attendance';

    protected $primaryKey = 'attendance_id';

    protected $fillable = ['attendance_record_id', 'student_adm_no', 'is_present'];
}
