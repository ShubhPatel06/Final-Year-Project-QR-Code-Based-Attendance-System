<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QRCode extends Model
{
    use HasFactory;

    protected $table = 'qr_codes';

    protected $primaryKey = 'qr_code_id';

    protected $fillable = ['attendance_record_id', 'expiry'];

    public function attendanceRecord()
    {
        return $this->belongsTo(AttendanceRecord::class, 'attendance_record_id');
    }
}
