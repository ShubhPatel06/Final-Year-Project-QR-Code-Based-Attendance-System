<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LectureGroups extends Model
{
    use HasFactory;

    protected $table = 'lecture_groups';

    protected $fillable = ['lecture_id', 'group_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }
}
