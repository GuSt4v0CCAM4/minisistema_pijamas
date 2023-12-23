<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\SaleRecord;
use Illuminate\Http\Request;
use MongoDB\Driver\Session;
use Illuminate\Support\Facades\Cookie;

class SalesRecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request){

        $selectedStore = $request->input('store', Cookie::get('selectedStore', '0'));
        Cookie::queue('selectedStore', $selectedStore, 1440);
        return view('sale.salesrecord', ['selectedStore' => $selectedStore]);
    }
    public function input(Request $request)
    {
        $selectedStore = request()->cookie('selectedStore', '0');
        $validatedData = $request->validate([
            'product' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'payment' => 'required',
        ]);
        $date = date('Y-m-d');
            SaleRecord::create([
               'product' => $request->input('product'),
               'price' => $request->input('price'),
               'quantity' => $request->input('quantity'),
               'payment' => $request->input('payment'),
               'id_user' => auth()->user()->id,
               'date' => $date,
               'id_store' => $selectedStore,
            ]);
            return redirect()->route('sales.record');
    }

}
