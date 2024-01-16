<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\InventoryRegister;
use App\Models\SaleRecord;
use Illuminate\Http\Request;
use MongoDB\Driver\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
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
        try{
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
            return redirect()->route('sales.record')->with('success', 'Se registro la venta correctamente!!');
        } catch (\Exception $e){
            return redirect()->route('sales.record')->with('error', 'Ocurrio un error a la hora de registrar la venta');
        }
    }
    public function get_products(Request $request)
    {
        $searchTerm = $request->input('searchTerm');
        $products = DB::table('inventory_register')
            ->select('id_product')
            ->where('id_product', 'like', '%' . $searchTerm . '%')
            ->get();
        return response()->json($products);
    }
    public function get_products_details(Request $request)
    {
        $productId = $request->input('id_product');
        $salePrice = DB::table('inventory_register')
            ->where('id_product', $productId)
            ->value('sale_price');
        return $salePrice;
    }

}
