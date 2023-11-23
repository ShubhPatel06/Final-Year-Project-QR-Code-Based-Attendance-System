<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $table = 'attendance_records';

    protected $primaryKey = 'record_id';

    public $timestamps = false;

    protected $fillable = ['lecture_id', 'group_id', 'date', 'start_time', 'end_time', 'qr_code_path', 'latitude', 'longitude', 'verification_token'];

    public function lecture()
    {
        return $this->belongsTo(Lectures::class, 'lecture_id', 'lecture_id');
    }

    public function group()
    {
        return $this->belongsTo(Groups::class, 'group_id', 'group_id');
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
