<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDivisions extends Model
{
    use HasFactory;

    protected $table = 'course_divisions';

    protected $primaryKey = 'division_id';

    protected $fillable = ['division_name', 'course_id'];

    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id', 'course_id');
    }
}
