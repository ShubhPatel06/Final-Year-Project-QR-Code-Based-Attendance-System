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

    public function attendanceRecord()
    {
        return $this->belongsTo(AttendanceRecord::class, 'attendance_record_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_adm_no', 'adm_no');
    }
}
