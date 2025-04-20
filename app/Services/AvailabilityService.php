<?php

namespace App\Services;


use App\Models\Appointment;
use App\Models\AppointmentType;
use App\Models\District;
use App\Models\Tehsil;
use App\Models\TimeSlot;
use App\Models\Unavailability;
use App\Models\User;
use Carbon\Carbon;

class AvailabilityService
{
    public function getNextDisponibilite($addressId, $appointmentTypeId): ?Carbon
    {
        $appointmentType = AppointmentType::findOrFail($appointmentTypeId);
        $duration = $appointmentType->duration_minutes;

        $now = Carbon::now();
        $endDate = $now->copy()->addDays(30);

        $slots = TimeSlot::with('appointmentTypes')
            ->where('address_id', $addressId)
            ->get();

        foreach ($slots as $slot) {
            if (!$slot->appointmentTypes->pluck('id')->contains($appointmentTypeId)) continue;

            $dates = $slot->getMatchingDates($now, $endDate);
            foreach ($dates as $date) {
                $start = Carbon::parse("$date {$slot->start_time}");
                $end = Carbon::parse("$date {$slot->end_time}");

                while ($start->copy()->addMinutes($duration) <= $end) {
                    $slotStart = $start->copy();
                    $slotEnd = $slotStart->copy()->addMinutes($duration);

                    if ($this->isSlotAvailable($slotStart, $slotEnd, $addressId)) {
                        return $slotStart;
                    }

                    $start->addMinutes(15);
                }
            }
        }
        return null;
    }

    private function isSlotAvailable(Carbon $start, Carbon $end, $addressId): bool
    {
        $hasAppointments = Appointment::where('address_id', $addressId)
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('start', [$start, $end])
                    ->orWhereBetween('end', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('start', '<', $start)->where('end', '>', $end);
                    });
            })->exists();

        $hasUnavailabilities = Unavailability::where(function ($query) use ($start, $end) {
            $query->whereBetween('start', [$start, $end])
                ->orWhereBetween('end', [$start, $end])
                ->orWhere(function ($q) use ($start, $end) {
                    $q->where('start', '<', $start)->where('end', '>', $end);
                });
        })->exists();

        return !$hasAppointments && !$hasUnavailabilities;
    }
}

