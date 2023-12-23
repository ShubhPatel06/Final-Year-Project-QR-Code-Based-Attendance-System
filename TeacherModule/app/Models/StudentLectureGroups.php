<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLectureGroups extends Model
{
    use HasFactory;

    protected $table = 'student_lecture_groups';

    protected $primaryKey = 'adm_no';

    protected $fillable = ['adm_no', 'lecture_id', 'group_id'];

    public $timestamps = false;

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function lectures()
    {
        return $this->hasMany(Lectures::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'adm_no', 'adm_no');
    }

    public function lecture()
    {
        return $this->belongsTo(Lectures::class, 'lecture_id', 'lecture_id');
    }

    public function group()
    {
        return $this->belongsTo(Groups::class, 'group_id', 'group_id');
    }
}
