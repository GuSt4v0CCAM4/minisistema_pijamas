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



        if ($selectedDate == '0') {
            return view('cashbalance', ['selectedDate' => $selectedDate]);
        }else {
            if ($selectedDate == '1'){
                $sale = DB::table('sale_record')
                    ->select('id_store','date','payment', DB::raw('SUM(price) as total'))
                    ->whereDate('date', $currentDate)
                    ->groupBy('date', 'payment', 'id_store')
                    ->get();
                $cash = DB::table('cash_record')
                    ->select('id_store','date','payment', DB::raw('SUM(amount) as total'))
                    ->whereDate('date', $currentDate)
                    ->groupBy('date', 'payment', 'id_store')
                    ->get();
                $tablecash = DB::table('profits')
                    ->select()
                    ->whereDate('date', $currentDate)
                    ->orderBy('date', 'asc')
                    ->get();
            } elseif ($selectedDate == '2'){
                $sale = DB::table('sale_record')
                    ->select('id_store','date','payment', DB::raw('SUM(price) as total'))
                    ->whereBetween('date', [$firstDayWeek, $lastDayWeek])
                    ->groupBy('date', 'payment', 'id_store')
                    ->get();
                $cash = DB::table('cash_record')
                    ->select('id_store','date','payment', DB::raw('SUM(amount) as total'))
                    ->whereBetween('date', [$firstDayWeek, $lastDayWeek])
                    ->groupBy('date', 'payment', 'id_store')
                    ->get();
                $tablecash = DB::table('profits')->select()->whereBetween('date', [$firstDayWeek, $lastDayWeek])
                    ->orderBy('date', 'asc')
                    ->get();
            } elseif ($selectedDate == '3'){
                $sale = DB::table('sale_record')
                    ->select('id_store','date','payment', DB::raw('SUM(price) as total'))
                    ->whereBetween('date', [$firstDayMonth, $lastDayMont])
                    ->groupBy('date', 'payment', 'id_store')
                    ->get();
                $cash = DB::table('cash_record')
                    ->select('id_store','date','payment', DB::raw('SUM(amount) as total'))
                    ->whereBetween('date', [$firstDayMonth, $lastDayMont])
                    ->groupBy('date', 'payment', 'id_store')
                    ->get();
                $tablecash = DB::table('profits')->select()->whereBetween('date', [$firstDayMonth, $lastDayMont])
                    ->orderBy('date', 'asc')
                    ->get();
            } elseif ($selectedDate == '4'){
                if (@isset($request->startDate, $request->endDate)){
                    $sale = DB::table('sale_record')
                        ->select('id_store','date','payment', DB::raw('SUM(price) as total'))
                        ->whereBetween('date', [$request->startDate, $request->endDate])
                        ->groupBy('date', 'payment', 'id_store')
                        ->get();
                    $cash = DB::table('cash_record')
                        ->select('id_store','date','payment', DB::raw('SUM(amount) as total'))
                        ->whereBetween('date', [$request->startDate, $request->endDate])
                        ->groupBy('date', 'payment', 'id_store')
                        ->get();
                    $tablecash = DB::table('profits')
                        ->select()
                        ->whereBetween('date', [$request->startDate, $request->endDate])
                        ->orderBy('date', 'asc')->get();
                }else{
                    $sale = DB::table('sale_record')
                        ->select('id_store','date','payment', DB::raw('SUM(price) as total'))
                        ->groupBy('date', 'payment', 'id_store')
                        ->get();
                    $cash = DB::table('cash_record')
                        ->select('id_store','date','payment', DB::raw('SUM(amount) as total'))
                        ->groupBy('date', 'payment', 'id_store')
                        ->get();
                    $tablecash = DB::table('profits')->select()->orderBy('date', 'asc')->get();
                }

            }
            return view('cashbalance', ['selectedDate' => $selectedDate, 'sale'=>$sale, 'cash'=>$cash, 'tablecash' => $tablecash]);
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
