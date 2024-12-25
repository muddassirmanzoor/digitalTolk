<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusChangeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'operation_id',
        'previous_status',
        'new_status',
        'updated_by',
    ];

    // Relationships
    public function operation()
    {
        return $this->belongsTo(OperationInformation::class, 'operation_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
