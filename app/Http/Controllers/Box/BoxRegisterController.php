<?php

namespace App\Http\Controllers\Box;

use App\Http\Controllers\Controller;
use App\Models\BoxRecord;
use App\Models\CashRecord;
use App\Models\Profit;
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
        $today = date('Y-m-d');
        $Date = $request->input('date', Cookie::get('Date', $today));
        $selectedStore= $request->input('store', Cookie::get('selectedStore', '0'));
        Cookie::queue('Date', $Date, 1440);
        Cookie::queue('selectedStore', $selectedStore, 1440);
        if ($Date != '0') {
            $datos = DB::table('sale_record')
                ->join('users', 'sale_record.id_user', '=', 'users.id') // inner join con la tabla 'user'
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
                'price' => 'required',
                'payment' => 'required',
            ]);
            $quantity = 1;
            $product = "-";
            $date = request()->cookie('Date', '0');
            SaleRecord::create([
                'product' => $product,
                'price' => $request->input('price'),
                'quantity' => $quantity,
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
                'payment' => 'required',
                'cash' => 'required',
            ]);
            CashRecord::create([
                'amount' => $request->input('amount'),
                'payment' => $request->input('payment'),
                'description' => $request->input('cash'),
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
            $comprobar = DB::table('profits')->where('id_store', $selectedStore)
                ->whereDate('date', $date)
                ->where('id_user', auth()->user()->id)
                ->get();
            if($comprobar->count() >  0){
                return redirect()->route('box.register')->with('danger', 'Ya hay un cierre de caja de este dia!!');
            }else{
                $sale = DB::table('sale_record')
                    ->select('id_store', 'date', 'payment', DB::raw('SUM(price) as total'))
                    ->where('id_store', $selectedStore)
                    ->whereDate('date', $date)
                    ->groupBy('date', 'payment', 'id_store')
                    ->get();
                $cash = DB::table('cash_record')
                    ->select('id_store', 'date', 'payment', DB::raw('SUM(amount) as total'))
                    ->where('id_store', $selectedStore)
                    ->whereDate('date', $date)
                    ->groupBy('date', 'payment', 'id_store')
                    ->get();
                $totalsale = 0.0;
                $payments = [0.0, 0.0, 0.0, 0.0, 0.0];
                foreach ($sale as $s) {
                    if ($s->payment == 1) {
                        $payments[0] += $s->total;
                    } elseif ($s->payment == 2) {
                        $payments[1] += $s->total;
                    } elseif ($s->payment == 3) {
                        $payments[2] += $s->total;
                    } elseif ($s->payment == 4) {
                        $payments[3] += $s->total;
                    } elseif ($s->payment == 7) {
                        $payments[4] += $s->total;
                    }
                    $totalsale += $s->total;
                }
                $totalcash = 0.0;
                $paymentc = [0.0, 0.0, 0.0, 0.0, 0.0];
                foreach ($cash as $c) {
                    if ($c->payment == 1) {
                        $paymentc[0] += $c->total;
                    } elseif ($c->payment == 2) {
                        $paymentc[1] += $c->total;
                    } elseif ($c->payment == 3) {
                        $paymentc[2] += $c->total;
                    } elseif ($c->payment == 4) {
                        $paymentc[3] += $c->total;
                    } elseif ($c->payment == 7) {
                        $paymentc[4] += $c->total;
                    }
                    $totalcash += $c->total;
                }
                $totalp = [0.0, 0.0, 0.0, 0.0, 0.0];
                for ($i = 0; $i < 4; $i++) {
                    $totalp[$i] = $payments[$i] - $paymentc[$i];
                }
                $payment = implode('|', $totalp);
                $ganacias = $totalsale - $totalcash;
                Profit::create([
                    'payment' => $payment,
                    'profit' => $totalsale,
                    'spend' => $totalcash,
                    'total' => $ganacias,
                    'date' => $date,
                    'id_user' => auth()->user()->id,
                    'id_store' => $selectedStore,
                ]);
                return redirect()->route('box.register')->with('success', 'Se registro el cierre correctamente!!');
            }
        } catch (\Exception $e){
           return redirect()->route('box.register')->with('error', 'Ocurrio un error a la hora de registrar el cierre');
       }
    }
}
