<?php

namespace App\Http\Controllers\Cash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\CashRecord;
use App\Models\SpentRecord;

class CashRecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $selectedStore = $request->input('store', Cookie::get('selectedStore', '0'));
        $selectedCash = $request->input('cash', Cookie::get('selectedCash', '0'));
        $selectedSpent = $request->input('spent', Cookie::get('selectedSpent', '0'));
        Cookie::queue('selectedCash', $selectedCash, 1440);
        Cookie::queue('selectedStore', $selectedStore, 1440);
        Cookie::queue('selectedSpent', $selectedSpent, 1440);
        return view('cash.cashrecord', ['selectedStore' => $selectedStore, 'selectedCash' => $selectedCash, 'selectedSpent' => $selectedSpent]);
    }
    public function input(Request $request)
    {
        try{
            $selectedStore = request()->cookie('selectedStore', '0');
            $selectedCash = request()->cookie('selectedCash', '0');
            if (!$request->filled('description')) {
                $request->merge(['description' => '-']);
            }
            $validatedData = $request->validate([
                'amount' => 'required',
                'description' => 'required',
            ]);
            $date = date('Y-m-d');
            CashRecord::create([
                'amount' => $request->input('amount'),
                'payment' => $selectedCash,
                'description' => $request->input('description'),
                'id_user' => auth()->user()->id,
                'date' => $date,
                'id_store' => $selectedStore,
            ]);
            return redirect()->route('cash.record')->with('success', 'El formulario se envio correctamente');
        } catch (\Exception $e){
            return redirect()->route('cash.record')->with('error', 'Ocurrio un error en el envio del formulario');
        }

    }
}
