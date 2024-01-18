<?php

namespace App\Http\Controllers\Box;

use App\Http\Controllers\Controller;
use App\Models\BoxRecord;
use App\Models\CashRecord;
use App\Models\SaleRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class BoxRegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $Date = $request->input('date', Cookie::get('Date', '0'));
        $selectedStore= $request->input('store', Cookie::get('selectedStore', '0'));
        Cookie::queue('Date', $Date, 1440);
        Cookie::queue('selectedStore', $selectedStore, 1440);
        if ($Date != '0') {
            $datos = DB::table('sale_record')->join('users', 'sale_record.id_user', '=', 'users.id') // inner join con la tabla 'user'
            ->select('sale_record.*', 'users.*')->where('id_store',$selectedStore)->whereDate('date', $Date)->get();
            $datos2 = DB::table('cash_record')->select()->where('id_store',$selectedStore)->whereDate('date', $Date)->get();

        } else {
            return view('box.boxregister', ['Date' => $Date, 'selectedStore' => $selectedStore]);
        }
        return view('box.boxregister', ['Date' => $Date, 'datos'=>$datos, 'datos2'=>$datos2,  'selectedStore' => $selectedStore]);
    }
    public function input_sale(Request $request)
    {
        try {
            $selectedStore = request()->cookie('selectedStore', '0');
            $validatedData = $request->validate([
                'product' => 'required',
                'price' => 'required',
                'quantity' => 'required',
                'payment' => 'required',
            ]);
            $date = request()->cookie('Date', '0');
            SaleRecord::create([
                'product' => $request->input('product'),
                'price' => $request->input('price'),
                'quantity' => $request->input('quantity'),
                'payment' => $request->input('payment'),
                'id_user' => auth()->user()->id,
                'date' => $date,
                'id_store' => $selectedStore,
            ]);
            return redirect()->route('box.register')->with('success_s', 'Se registro la venta correctamente!!');
        } catch (\Exception $e){
            return redirect()->route('box.register')->with('error_s', 'Ocurrio un error a la hora de registrar la venta');
        }
    }
    public function input_cash(Request $request)
    {
        $selectedStore = request()->cookie('selectedStore', '0');
        $date = request()->cookie('Date', '0');
        if (!$request->filled('description')) {
            $request->merge(['description' => '-']);
        }
        try{
            $validatedData = $request->validate([
                'amount' => 'required',
                'description' => 'required',
                'cash' => 'required',
            ]);
            CashRecord::create([
                'amount' => $request->input('amount'),
                'payment' => $request->input('cash'),
                'description' => $request->input('description'),
                'id_user' => auth()->user()->id,
                'date' => $date,
                'id_store' => $selectedStore,
            ]);
        }catch (\Exception $e){
            return redirect()->route('box.register')->with('error_c', 'Ocurrio un error a la hora de registrar el pago');
        }
        return redirect()->route('box.register')->with('success_c', 'Se registro el pago correctamente!!');
    }
    public function registerclose(Request $request)
    {
        try {
            $date = request()->cookie('Date', '0');
            $selectedStore = request()->cookie('selectedStore', '0');
            BoxRecord::create([
                'sale' => $request->input('saleTotal'),
                'spent' => $request->input('cashTotal'),
                'profit' => $request->input('profit'),
                'date' => $date,
                'id_user' => auth()->user()->id,
                'id_store' => $selectedStore,
            ]);
            return redirect()->route('box.register')->with('success', 'Se registro el cierre correctamente!!');
        } catch (\Exception $e){
           return redirect()->route('box.register')->with('error', 'Ocurrio un error a la hora de registrar el cierre');
       }
    }
}
