<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRecord extends Model
{
    use HasFactory;
    protected $table = 'cash_record';
    public $timestamps = false;
    protected $fillable = [
        'id_reg',
        'amount',
        'payment',
        'description',
        'id_user',
        'date',
        'id_store',
    ];
}
