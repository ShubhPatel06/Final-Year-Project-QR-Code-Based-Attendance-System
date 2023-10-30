<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lectures extends Model
{
    use HasFactory;

    protected $table = 'lectures';

    protected $primaryKey = 'lecture_id';

    protected $fillable = ['lecture_code', 'lecture_name', 'faculty_id', 'course_id', 'lecturer_id', 'total_hours', 'start_time', 'end_time'];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'lecture_groups', 'lecture_id', 'group_id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }
}
