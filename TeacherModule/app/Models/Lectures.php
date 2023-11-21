<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lectures extends Model
{
    use HasFactory;

    protected $table = 'lectures';

    protected $primaryKey = 'lecture_id';

    protected $fillable = ['lecture_code', 'lecture_name', 'faculty_id', 'course_id', 'lecturer_id', 'total_hours'];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id', 'faculty_id');
    }

    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id', 'course_id');
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturers::class, 'lecturer_id', 'user_id');
    }

    // public function groups()
    // {
    //     return $this->belongsToMany(Groups::class, 'lecture_groups', 'lecture_id', 'group_id');
    // }


    public function lectureGroups()
    {
        return $this->hasMany(LectureGroups::class, 'lecture_id', 'lecture_id');
    }

    public function groups()
    {
        return $this->hasManyThrough(Groups::class, LectureGroups::class, 'lecture_id', 'group_id', 'lecture_id', 'group_id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }
}
