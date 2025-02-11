<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OperationImage extends Model
{
    protected $fillable = ['operational_id','operation_information_id', 'image_path'];

    public function operation()
    {
        return $this->belongsTo(OperationInformation::class);
    }
}
