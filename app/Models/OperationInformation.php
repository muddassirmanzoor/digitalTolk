<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OperationInformation extends Model
{
    use HasFactory;
    protected $table = 'operation_information';

    protected $fillable = [
        'operational_id',
        'agent_id',
        'agent_number',
        'people_quantity',
        'nationality',
        'voucher_number',
        'group_leader_name',
        'group_leader_number',
        'group_numbers',
        'status',
        'created_by',
        'edited_by',
    ];

    public static function boot()
    {
        parent::boot();

        // Hook into the updating event
        static::updating(function ($model) {
            // Check if the 'status' field is being changed
            if ($model->isDirty('status')) {
                // Log the status change
                \App\Models\StatusChangeLog::create([
                    'operation_id' => $model->id,
                    'previous_status' => $model->getOriginal('status'),
                    'new_status' => $model->status,
                    'updated_by' => auth()->user()->id, // Ensure user is authenticated
                ]);
            }
            // Get all changed fields except 'status'
            $changedFields = collect($model->getDirty())->except(['status']);

            // Only proceed with audit logging if there are changes other than 'status'
            if ($model->status == 1 && $changedFields->isNotEmpty()) {
                foreach ($changedFields as $field => $newValue) {
                    $oldValue = $model->getOriginal($field);

                    AuditLog::create([
                        'section' => 'Operation Information',
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

    public function arrival()
    {
        return $this->hasOne(ArrivalSection::class, 'operational_id', 'operational_id');
    }

    public function movement()
    {
        return $this->hasOne(MovementSection::class, 'operational_id', 'operational_id');
    }

    public function mzarat()
    {
        return $this->hasOne(MzaratSection::class, 'operational_id', 'operational_id');
    }

    public function departure()
    {
        return $this->hasOne(DepartureSection::class, 'operational_id', 'operational_id');
    }

    public function agent()
    {
        return $this->hasOne(Agent::class, 'id', 'agent_id');
    }

    public function audit()
    {
        return $this->hasMany(AuditLog::class, 'operational_id', 'operational_id');
    }

    public function comments()
    {
        return $this->hasMany(EditComment::class, 'operational_id', 'operational_id');
    }
    public function images()
    {
        return $this->hasMany(OperationImage::class,'operational_id', 'operational_id');
    }
}
