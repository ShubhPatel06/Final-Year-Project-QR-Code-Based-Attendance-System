<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGroups extends Model
{
    use HasFactory;
    protected $table = 'student_groups';

    protected $primaryKey = 'group_id';

    protected $fillable = ['adm_no', 'group_id'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'adm_no', 'adm_no');
    }

    public function group()
    {
        return $this->belongsTo(Groups::class, 'group_id', 'group_id');
    }
}
