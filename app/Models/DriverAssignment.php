<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverAssignment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function operation()
    {
        return $this->belongsTo(OperationInformation::class, 'operation_id');
    }

    public function section()
    {
        switch ($this->section_type) {
            case 'arrival':
                return $this->belongsTo(ArrivalSection::class, 'section_id');
            case 'departure':
                return $this->belongsTo(DepartureSection::class, 'section_id');
            case 'movement':
                return $this->belongsTo(MovementSection::class, 'section_id');
            case 'mzarat':
                return $this->belongsTo(MzaratSection::class, 'section_id');
        }
    }
}
