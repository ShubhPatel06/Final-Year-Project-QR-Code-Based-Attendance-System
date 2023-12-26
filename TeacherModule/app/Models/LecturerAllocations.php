<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LecturerAllocations extends Model
{
    use HasFactory;

    protected $table = 'lecturer_allocations';

    protected $primaryKey = 'lecturer_id';

    protected $fillable = ['lecturer_id', 'lecture_id', 'group_id'];

    public $timestamps = false;

    public function lecturer()
    {
        return $this->belongsTo(Lecturers::class, 'lecturer_id', 'user_id');
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
