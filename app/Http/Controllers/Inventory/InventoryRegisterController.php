<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class InventoryRegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index( Request $request){
        $selectedStore = $request->input('store', Cookie::get('selectedStore', '0'));
        Cookie::queue('selectedStore', $selectedStore, 1440);
        return view('inventory.inventoryregister', ['selectedStore' => $selectedStore]);
    }
    public function register( Request $request){
        $selectedStore = $request->input('store', Cookie::get('selectedStore', '0'));
        $validatedData = $request->validate([
            'description' => 'required',
            'gender' => 'required',
            'size' => 'required',
            'sale_price' => 'required',
            'brand' => 'required',
            'color' => 'required',
        ]);
        $description = $request->input('description');
        $gender = $request->input('gender');
        $size = $request->input('size');
        $sale_price = $request->input('sale_price');
        $brand = $request->input('brand');
        $color = $request->input('color');
        return view('inventory.inventoryregister',['description'=>$description, 'selectedStore' => $selectedStore,
            'gender'=>$gender, 'size'=>$size, 'sale_price'=>$sale_price, 'brand'=>$brand, 'color'=>$color]);
    }
}
