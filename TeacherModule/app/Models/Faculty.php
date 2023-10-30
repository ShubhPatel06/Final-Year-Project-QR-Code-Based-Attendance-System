<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $table = 'faculty';
    protected $primaryKey = 'faculty_id';

    protected $fillable = ['faculty_name'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function lecturers()
    {
        return $this->hasMany(Lecturer::class);
    }

    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }
}
