<?php

namespace App\Http\Controllers;

use App\Models\BoxRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class CashBalanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', Cookie::get('selectedDate', '0'));
        $selectedStore = $request->input('store', Cookie::get('selectedStore', '0'));
        // ---- FUNCIONES DATE
        $currentDate = date('Y-m-d');
        $currentDayWeek = date('N', strtotime($currentDate));
        //primer dia de la semana
        $firstDayWeek = date('Y-m-d', strtotime('-' . $currentDayWeek . ' days', strtotime($currentDate)));
        //ultimo dia de la semana
        $lastDayWeek = date('Y-m-d', strtotime('+' . (7 - $currentDayWeek) . ' days', strtotime($currentDate)));
        $firstDayMonth = date('Y-m-01');
        $lastDayMont = date('Y-m-t');
        // ---- FUNCIONES DATE
        Cookie::queue('selectedDate', $selectedDate, 1440);
        Cookie::queue('selectedStore', $selectedStore, 1440);



        if ($selectedDate == '0') {
            return view('cashbalance', ['selectedDate' => $selectedDate, 'selectedStore' => $selectedStore]);
        }else {
            if ($selectedDate == '1'){
                $sale = DB::table('sale_record')->select()->where('id_store',$selectedStore)->whereDate('date', $currentDate)->get();
                $cash = DB::table('cash_record')->select()->where('id_store',$selectedStore)->whereDate('date', $currentDate)->get();
                $tablecash = DB::table('cash_close_register')->select()->whereDate('date', $currentDate)->get();
            } elseif ($selectedDate == '2'){
                $sale = DB::table('sale_record')->select()->where('id_store', $selectedStore)->whereBetween('date', [$firstDayWeek, $lastDayWeek])->get();
                $cash = DB::table('cash_record')->select()->where('id_store', $selectedStore)->whereBetween('date', [$firstDayWeek, $lastDayWeek])->get();
                $tablecash = DB::table('cash_close_register')->select()->whereBetween('date', [$firstDayWeek, $lastDayWeek])->get();
            } elseif ($selectedDate == '3'){
                $sale = DB::table('sale_record')->select()->where('id_store', $selectedStore)->whereBetween('date', [$firstDayMonth, $lastDayMont])->get();
                $cash = DB::table('cash_record')->select()->where('id_store', $selectedStore)->whereBetween('date', [$firstDayMonth, $lastDayMont])->get();
                $tablecash = DB::table('cash_close_register')->select()->whereBetween('date', [$firstDayMonth, $lastDayMont])->get();
            }
            return view('cashbalance', ['selectedDate' => $selectedDate, 'sale'=>$sale, 'cash'=>$cash, 'selectedStore' => $selectedStore, 'tablecash' => $tablecash]);
        }

    }
    public function register(Request $request)
    {
        try {
            $date = date('Y-m-d');
            $selectedStore = request()->cookie('selectedStore', '0');
            BoxRecord::create([
                'sale' => $request->input('saleTotal'),
                'spent' => $request->input('cashTotal'),
                'profit' => $request->input('profit'),
                'date' => $date,
                'id_user' => auth()->user()->id,
                'id_store' => $selectedStore,
            ]);
            return redirect()->route('cash.balance')->with('success', 'Se registro el cierre correctamente!!');
        } catch (\Exception $e){
            return redirect()->route('cash.balance')->with('error', 'Ocurrio un error a la hora de registrar el cierre');
        }
    }
}
