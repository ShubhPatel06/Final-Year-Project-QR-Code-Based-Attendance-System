<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturers extends Model
{
    use HasFactory;

    protected $table = 'lecturers';

    protected $primaryKey = 'lecturer_id';

    protected $fillable = [
        'user_id',
        'faculty_id'
    ];
}
