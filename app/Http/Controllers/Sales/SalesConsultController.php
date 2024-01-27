<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\SaleConsult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class SalesConsultController extends Controller
{
    public function index( Request $request){
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
        if($selectedDate == '0'){
            return view('sale.salesconsult', ['selectedDate' => $selectedDate, 'selectedStore' => $selectedStore]);
        }else{
            if ($selectedDate == '1'){
                $datos = DB::table('sale_record')->join('users', 'sale_record.id_user', '=', 'users.id') // inner join con la tabla 'user'
                ->select('sale_record.*', 'users.*')->where('id_store',$selectedStore)->whereDate('date', $currentDate)->get();
            } elseif ($selectedDate == '2'){
                $datos = DB::table('sale_record')->join('users', 'sale_record.id_user', '=', 'users.id') // inner join con la tabla 'user'
                ->select('sale_record.*', 'users.*')->where('id_store',$selectedStore)->whereBetween('date', [$firstDayWeek, $lastDayWeek])->get();
            } elseif ($selectedDate == '3'){
                $datos = DB::table('sale_record')->join('users', 'sale_record.id_user', '=', 'users.id') // inner join con la tabla 'user'
                ->select('sale_record.*', 'users.*')->where('id_store',$selectedStore)->whereBetween('date', [$firstDayMonth, $lastDayMont])->get();
            }
            return view('sale.salesconsult', ['selectedDate' => $selectedDate, 'datos'=>$datos, 'selectedStore' => $selectedStore]);
        }




        //return $firstDayWeek.' '.$lastDayWeek;
    }
    public function edit(Request $request){
        $id = $request->input('id');
        $datos = DB::table('sale_record')->select()->where('id_reg',$id)->get();
        return view('sale.editsale', ['datos'=>$datos]);
    }
    public function update(Request $request){
        $validatedData = $request->validate([
            'price' => 'required',
            'payment' => 'required',
        ]);
        try{
            $id = $request->input('id');
            $product = "-";
            $price = $request->input('price');
            $quantity = 1;
            $payment = $request->input('payment');
            DB::table('sale_record')->where('id_reg', $id)
                ->update([
                    'product' => $product,
                    'price' => $price,
                    'quantity' => $quantity,
                    'payment' => $payment
                ]);
        }catch (\Exception $e){
            return redirect()->route('box.register')->with('error_edit_s', 'Ocurrio un error a la hora de editar la venta');
        }
        return redirect()->route('box.register')->with('success_edit_s', 'Se edito la venta correctamente!!');
    }
    public function delete(Request $request){
        $id = $request->input('id');
        try{
            DB::table('sale_record')->where('id_reg', $id)->delete();
        }catch (\Exception $e){
            return redirect()->route('sales.consult')->with('error', 'Ocurrio un error a la hora de eliminar la venta');
        }
        return redirect()->route('sales.consult')->with('success', 'Se elimino la venta correctamente!!');
    }
    //
}
