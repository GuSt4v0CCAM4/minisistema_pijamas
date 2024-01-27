<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    use HasFactory;
    protected $table = 'profits';
    public $timestamps = false;
    protected $fillable = [
        'id_reg',
        'payment',
        'profit',
        'spend',
        'total',
        'date',
        'id_user',
        'id_store',
    ];
}
