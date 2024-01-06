@extends('layouts.app')
@section('content')
    <div class="container">

        <form method="GET" action="{{route('inventory.register')}}">
            <div class="form-floating">
                <select class="form-select" name="store" id="store" aria-label="Floating label select example">
                    <option selected disabled value="0">--Seleccione una Tienda--</option>
                    <option value="1" {{ $selectedStore == '1' ? 'selected' : '' }}>San Camilo</option>
                    <option value="2" {{ $selectedStore == '2' ? 'selected' : '' }}>Maternos</option>
                    <option value="3" {{ $selectedStore == '3' ? 'selected' : '' }}>Maomas</option>
                    <option value="4" {{ $selectedStore == '4' ? 'selected' : '' }}>Camana</option>
                </select>
                <!---<button type="submit">Mostrar registros</button> -->
                <label for="store">Tienda:</label>
            </div>
        </form>
        <div class="container p-4 my-5 bg-white" >
            <h2 align="center" class="mb-5">Registrar Producto</h2>
            <form class="row g-3 needs-validation" method="POST" action="{{route('product.register')}}">
                @csrf
                <div class="col-md-4">
                    <label for="validationCustom01" class="form-label">Descripcion:</label>
                    <input type="text" class="form-control" id="validationCustom01" value="" name="description">
                    <div class="valid-feedback">
                        Elija un producto
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="validationCustom04" class="form-label">Sexo:</label>
                    <select class="form-select" id="validationCustom04" name="gender" required>
                        <option selected disabled value="">Elije una opción</option>
                        <option value="M"> Masculino</option>
                        <option value="F"> Femenino</option>
                    </select>
                    <div class="invalid-feedback">
                        Elije el sexo
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="validationCustom04" class="form-label">Talla:</label>
                    <select class="form-select" id="validationCustom04" name="size" required>
                        <option selected disabled value="">Elije una opción</option>
                        <option value="N"> 4-16</option>
                        <option value="S"> Small</option>
                        <option value="M"> Medium</option>
                        <option value="L"> Large</option>
                        <option value="XL"> XL</option>
                    </select>
                    <div class="invalid-feedback">
                        Elije la talla
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="validationCustomUsername" class="form-label">Precio de Venta:</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">S/.</span>
                        <input type="number" step="any" min="1"  pattern="^[0-9]" class="form-control" id="validationCustomUsername"
                               name="sale_price" aria-describedby="inputGroupPrepend" required>
                        <div class="invalid-feedback">
                            Escriba el Monto
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="validationCustom01" class="form-label">Marca:</label>
                    <input type="text" class="form-control" id="validationCustom01" value="" name="brand">
                    <div class="valid-feedback">
                        Elija un producto
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="validationCustom01" class="form-label">Color:</label>
                    <input type="text" class="form-control" id="validationCustom01" value="" name="color">
                    <div class="valid-feedback">
                        Elija un producto
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Registrar Producto</button>
                </div>
            </form>
        </div>
    </div>
    <div class="container">
        @if(isset($description))
            @php
            $brandM = strtoupper($brand);
            $consonantes = preg_replace('/[aeiouAEIOU]+/', '', $brandM);
            $code_brand= substr($consonantes,0,3);
            //CODE COLOR
            $colorM = strtoupper($color);
            //$c_color = preg_replace('/[aeiouAEIOU]+/', '', $colorM);
            $code_color = substr($colorM, 0, 3);
            //CODE DESCRIPTION
            $descriptionM = strtoupper($description);
            $arrayPalabra = explode(" ", $descriptionM); //lo convertimos en unn array
            $code_description = "";

            foreach ($arrayPalabra as $palabra){
                if (strlen($palabra) >= 1){
                    $dosLetras = substr($palabra, 0, 1);
                    $code_description .= $dosLetras;
                }
            }
            $id = $gender."-".$code_brand."-".$size."-".$code_description."-".$code_color
            @endphp
            <h2>El codigo seria: {{$id}}</h2>
        @endif

    </div>
    <script>
        document.getElementById('store').addEventListener('change', function() {
            this.form.submit();
        })
    </script>
@endsection
