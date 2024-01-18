<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoxRecord extends Model
{
    use HasFactory;
    protected $table = 'cash_close_register';
    public $timestamps = false;
    protected $fillable = [
        'id_reg',
        'sale',
        'spent',
        'profit',
        'date',
        'id_user',
        'id_store',
    ];
}
