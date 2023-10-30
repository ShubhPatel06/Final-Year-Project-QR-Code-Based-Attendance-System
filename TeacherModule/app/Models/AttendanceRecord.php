<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $table = 'attendance_records';

    protected $primaryKey = 'record_id';

    protected $fillable = ['lecture_id', 'date'];

    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }

    public function studentAttendance()
    {
        return $this->hasMany(StudentAttendance::class, 'attendance_record_id');
    }

    public function qrCode()
    {
        return $this->hasOne(QRCode::class, 'attendance_record_id');
    }
}
