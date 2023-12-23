<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpentRecord extends Model
{
    use HasFactory;
    protected $table = 'spent_record';
    public $timestamps = false;
    protected $fillable = [
        'id_reg',
        'amount',
        'type',
        'description',
        'id_user',
        'date',
        'id_store',
    ];
}
