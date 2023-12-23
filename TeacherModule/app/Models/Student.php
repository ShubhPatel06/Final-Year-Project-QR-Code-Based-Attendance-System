<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $primaryKey = 'adm_no';

    protected $fillable = ['user_id', 'adm_no',  'course_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id', 'course_id');
    }

    public function studentGroups()
    {
        return $this->hasMany(StudentLectureGroups::class, 'adm_no', 'adm_no');
    }

    public function studentAttendance()
    {
        return $this->hasMany(StudentAttendance::class, 'student_adm_no', 'adm_no');
    }
}
