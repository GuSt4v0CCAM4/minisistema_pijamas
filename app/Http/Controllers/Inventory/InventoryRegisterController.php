<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class InventoryRegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index( Request $request){
        $selectedStore = $request->input('store', Cookie::get('selectedStore', '0'));
        Cookie::queue('selectedStore', $selectedStore, 1440);
        return view('inventory.inventoryregister', ['selectedStore' => $selectedStore]);
    }
    public function register( Request $request){
        try {
            $selectedStore = $request->input('store', Cookie::get('selectedStore', '0'));
            $validatedData = $request->validate([
                'description' => 'required',
                'gender' => 'required',
                'size' => 'required',
                'sale_price' => 'required',
                'brand' => 'required',
                'color' => 'required',
            ]);
            $date = date('Y-m-d');
            $description = $request->input('description');
            $gender = $request->input('gender');
            $size = $request->input('size');
            $sale_price = $request->input('sale_price');
            $brand = $request->input('brand');
            $color = $request->input('color');
            //GENERAR CODIGO/ID

            //CODE MARCA
            $brandM = strtoupper($brand);
            $consonantes = preg_replace('/[aeiouAEIOU]+/', '', $brandM);
            $code_brand = substr($consonantes, 0, 3);
            //CODE COLOR
            $colorM = strtoupper($color);
            //$c_color = preg_replace('/[aeiouAEIOU]+/', '', $colorM);
            $code_color = substr($colorM, 0, 3);
            //CODE DESCRIPTION
            $descriptionM = strtoupper($description);
            $arrayPalabra = explode(" ", $descriptionM); //lo convertimos en unn array
            $code_description = "";

            foreach ($arrayPalabra as $palabra) {
                if (strlen($palabra) >= 1) {
                    $dosLetras = substr($palabra, 0, 1);
                    $code_description .= $dosLetras;
                }
            }
            $id = $gender . "-" . $code_brand . "-" . $size . "-" . $code_description . "-" . $code_color;
            InventoryRegister::create([
                'id_product' => $id,
                'description' => $description,
                'gender' => $gender,
                'size' => $size,
                'sale_price' => $sale_price,
                'brand' => $brand,
                'color' => $color,
                'date_entry' => $date,
                'id_store' => $selectedStore,
            ]);


            return redirect()->route('inventory.register')->with('success', 'El registro de inventario se realizo con exito!!');
        }catch (\Exception $e){
            return redirect()->route('inventory.register')->with('error', 'Ocurrio un error en el registro!!');
        }
    }
}
