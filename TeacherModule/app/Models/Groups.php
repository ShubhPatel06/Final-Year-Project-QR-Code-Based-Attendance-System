<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    use HasFactory;

    protected $table = 'semester_groups';

    protected $primaryKey = 'group_id';

    protected $fillable = ['group_name', 'year', 'semester', 'division_id'];

    public function division()
    {
        return $this->belongsTo(CourseDivisions::class, 'division_id', 'division_id');
    }

    public function lectures()
    {
        return $this->belongsToMany(Lectures::class, 'lecture_groups', 'group_id', 'lecture_id');
    }

    public function lectureGroups()
    {
        return $this->hasMany(StudentLectureGroups::class, 'group_id', 'group_id');
    }
}
