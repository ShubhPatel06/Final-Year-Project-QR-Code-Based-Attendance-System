<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LectureGroups extends Model
{
    use HasFactory;

    protected $table = 'lecture_groups';

    protected $primaryKey = 'group_id';

    protected $fillable = ['lecture_id', 'group_id'];

    public $timestamps = false;

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

    public function lecture()
    {
        return $this->belongsTo(Lectures::class, 'lecture_id', 'lecture_id');
    }

    public function group()
    {
        return $this->belongsTo(Groups::class, 'group_id', 'group_id');
    }
}
