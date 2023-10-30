<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $primaryKey = 'adm_no';

    protected $fillable = ['user_id', 'adm_no',  'course_id', 'year_of_study', 'semester', 'group_id'];
}
