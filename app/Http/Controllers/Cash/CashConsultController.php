<?php

namespace App\Http\Controllers\Cash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class CashConsultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', Cookie::get('selectedDate', '0'));
        $selectedStore = $request->input('store', Cookie::get('selectedStore', '0'));
        //$selectedStore = request()->cookie('selectedStore', '0');
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
            return view('cash.cashconsult', ['selectedDate' => $selectedDate, 'selectedStore' => $selectedStore]);
        }else {
            if ($selectedDate == '1'){
                $datos = DB::table('cash_record')->select()->where('id_store',$selectedStore)->whereDate('date', $currentDate)->get();
            } elseif ($selectedDate == '2'){
                $datos = DB::table('cash_record')->select()->where('id_store',$selectedStore)->whereBetween('date', [$firstDayWeek, $lastDayWeek])->get();
            } elseif ($selectedDate == '3'){
                $datos = DB::table('cash_record')->select()->where('id_store',$selectedStore)->whereBetween('date', [$firstDayMonth, $lastDayMont])->get();
            }
            return view('cash.cashconsult', ['selectedDate' => $selectedDate, 'datos'=>$datos, 'selectedStore' => $selectedStore]);
        }

    }
}
