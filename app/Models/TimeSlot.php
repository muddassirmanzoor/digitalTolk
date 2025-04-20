<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $casts = [
        'weekdays' => 'array',
    ];

    public function appointmentTypes()
    {
        return $this->belongsToMany(AppointmentType::class, 'time_slot_appointment_type');
    }

    public function getMatchingDates(Carbon $start, Carbon $end)
    {
        $dates = [];

        // Check for a specific date (dynamic slot)
        if ($this->specific_date) {
            $date = Carbon::parse($this->specific_date);
            if ($date->between($start, $end)) {
                $dates[] = $date->toDateString();
            }
        } elseif ($this->weekdays) {
            // Handle weekdays
            $weekdays = is_array($this->weekdays) ? $this->weekdays : json_decode($this->weekdays, true);
            $period = CarbonPeriod::create($start, $end);

            foreach ($period as $date) {
                if (in_array(strtolower($date->format('l')), $weekdays)) {
                    $dates[] = $date->toDateString();
                }
            }
        }

        return $dates;
    }


}
