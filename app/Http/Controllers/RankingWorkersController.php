<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class RankingWorkersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request){
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
            return view('workerranking', ['selectedDate' => $selectedDate]);
        }else {
            if ($selectedDate == '1'){
                $tablecash = DB::table('profits')
                    ->join('users', 'profits.id_user', '=', 'users.id')
                    ->select('users.name','profits.id_user', DB::raw('SUM(total) as total'))
                    ->whereDate('date', $currentDate)
                    ->orderBy('total', 'desc')
                    ->groupBy( 'users.name','profits.id_user')
                    ->get();
            } elseif ($selectedDate == '2'){
                $tablecash = DB::table('profits')
                    ->join('users', 'profits.id_user', '=', 'users.id')
                    ->select('users.name','profits.id_user', DB::raw('SUM(total) as total'))
                    ->whereBetween('date', [$firstDayWeek, $lastDayWeek])
                    ->orderBy('total', 'desc')
                    ->groupBy( 'users.name','profits.id_user')
                    ->get();
            } elseif ($selectedDate == '3'){
                $tablecash = DB::table('profits')
                    ->join('users', 'profits.id_user', '=', 'users.id')
                    ->select('users.name','profits.id_user', DB::raw('SUM(total) as total'))
                    ->whereBetween('date', [$firstDayMonth, $lastDayMont])
                    ->orderBy('total', 'desc')
                    ->groupBy( 'users.name','profits.id_user')
                    ->get();
            } elseif ($selectedDate == '4'){
                if (@isset($request->startDate, $request->endDate)){
                    $tablecash = DB::table('profits')
                        ->join('users', 'profits.id_user', '=', 'users.id')
                        ->select('users.name','profits.id_user', DB::raw('SUM(total) as total'))
                        ->whereBetween('date', [$request->startDate, $request->endDate])
                        ->orderBy('total', 'desc')
                        ->groupBy( 'users.name','profits.id_user')
                        ->get();
                }else{
                    $tablecash = DB::table('profits')
                        ->join('users', 'profits.id_user', '=', 'users.id')
                        ->select('users.name','profits.id_user', DB::raw('SUM(total) as total'))
                        ->orderBy('total', 'desc')
                        ->groupBy( 'users.name','profits.id_user')
                        ->get();
                }

            }
            return view('workerranking', ['selectedDate' => $selectedDate, 'tablecash' => $tablecash]);
        }
    }
}
