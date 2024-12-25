<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DepartureSection extends Model
{
    use HasFactory;
    protected $table = 'departure_section';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        // Hook into the updating event
        static::updating(function ($model) {
            // Check the status in Operational Information
            if ($model->operation && $model->operation->status == 1) {

                foreach ($model->getDirty() as $field => $newValue) {
                    $oldValue = $model->getOriginal($field);

                    AuditLog::create([
                        'section' => 'Departure',
                        'record_id' => $model->id,
                        'operational_id' => $model->operational_id,
                        'user_id' => Auth::id(),
                        'field' => $field,
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                    ]);
                }
            }
        });
    }

    public function operation()
    {
        return $this->belongsTo(OperationInformation::class, 'operational_id', 'operational_id');
    }

    public function driverAssignment()
    {
        return $this->hasOne(DriverAssignment::class, 'section_id', 'id')
            ->where('section_type', 'departure'); // Filter by section_type
    }
}
