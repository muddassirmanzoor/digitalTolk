<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentType extends Model
{
    protected $fillable = ['name', 'duration_minutes'];

    public function timeSlots()
    {
        return $this->belongsToMany(TimeSlot::class, 'time_slot_appointment_type');
    }
}
