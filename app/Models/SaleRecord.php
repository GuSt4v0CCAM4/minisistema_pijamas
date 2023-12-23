<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleRecord extends Model
{
    use HasFactory;
    protected $table = 'sale_record';
    public $timestamps = false;
    protected $fillable = [
        'product',
        'price',
        'quantity',
        'payment',
        'id_user',
        'date',
        'id_store',
    ];
}
