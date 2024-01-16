<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryRegister extends Model
{
    use HasFactory;
    protected $table = 'inventory_register';
    public $timestamps = false;
    protected $fillable = [
        'id_product',
        'description',
        'gender',
        'size',
        'sale_price',
        'brand',
        'color',
        'date_entry',
        'id_store',
    ];
}
