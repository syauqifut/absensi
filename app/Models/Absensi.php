<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;
    
    protected $table = 'absensi';

    protected $fillable = [
        'user_id',
        'status',
        'start_at',
        'end_at',
        'keterangan',
        'is_active',
    ];
}
