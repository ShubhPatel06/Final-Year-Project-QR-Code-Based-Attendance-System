<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturers extends Model
{
    use HasFactory;

    protected $table = 'lecturers';

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_id',
        'faculty_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id', 'faculty_id');
    }

    // public function lectures()
    // {
    //     return $this->hasMany(Lectures::class);
    // }

    public function lecturerAllocation()
    {
        return $this->hasMany(LecturerAllocations::class, 'lecturer_id', 'lecturer_id');
    }
}
