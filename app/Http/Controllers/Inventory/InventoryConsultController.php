<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
class InventoryConsultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index( Request $request)
    {
        $selectedDate = $request->input('date', Cookie::get('selectedDate', '0'));
        $selectedStore = $request->input('store', Cookie::get('selectedStore', '0'));
        Cookie::queue('selectedDate', $selectedDate, 1440);
        Cookie::queue('selectedStore', $selectedStore, 1440);
        $datos = DB::table('inventory_register')->select()->where('id_store',$selectedStore)->get();
        return view('inventory.inventoryconsult', ['selectedDate' => $selectedDate, 'datos'=>$datos, 'selectedStore' => $selectedStore]);
    }
}
