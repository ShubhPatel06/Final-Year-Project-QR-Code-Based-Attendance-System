<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    use HasFactory;

    protected $table = 'groups';

    protected $primaryKey = 'group_id';

    protected $fillable = ['group_name', 'year', 'semester'];

    public function lectures()
    {
        return $this->belongsToMany(Lectures::class, 'lecture_groups', 'group_id', 'lecture_id');
    }

    public function lectureGroups()
    {
        return $this->hasMany(LectureGroups::class, 'group_id', 'group_id');
    }
}
