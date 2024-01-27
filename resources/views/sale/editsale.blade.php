@extends('layouts.app')
@section('content')

    <div class="container p-4 my-5 bg-white" >
        <h2 align="center" class="mb-5">Editar Venta</h2>
        @foreach($datos as $dato)
            @php
                $id = $dato->id_reg;
            @endphp
        <form class="row g-3 needs-validation" method="POST" action="{{route('sales.update', ['id' => $id])}}">

            @csrf
            <div class="col-md-4">
                <label for="sale_price" class="form-label">Precio</label>
                <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend">S/.</span>
                    <input type="number" step="any" min="1"  pattern="^[0-9]" class="form-control" id="sale_price"
                           name="price" aria-describedby="inputGroupPrepend" value="{{$dato->price}}" required>
                    <div class="invalid-feedback">
                        Elija un precio
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label for="validationCustom04" class="form-label">Medio de Pago:</label>
                <select class="form-select" id="validationCustom04" name="payment" required>
                    <option  disabled value="">Elije una opción</option>
                    <option value="1" {{$dato->payment == 1 ? 'selected' : ''}}> Efectivo</option>
                    <option value="2" {{$dato->payment == 2 ? 'selected' : ''}}> Transferencia</option>
                    <option value="3" {{$dato->payment == 3 ? 'selected' : ''}}> Yape</option>
                    <option value="4" {{$dato->payment == 4 ? 'selected' : ''}}> Plin</option>
                    <option value="7" {{$dato->payment == 7 ? 'selected' : ''}}> Tarjeta Visa</option>
                </select>
                <div class="invalid-feedback">
                    Elije un medio de pago
                </div>
            </div>

            <div class="col-12">
                <button class="btn btn-primary" type="submit">Editar Venta</button>
            </div>
        </form>
        @endforeach
        <br>
        @if(session('success'))
            <div class="col-md-4 alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="col-md-4 alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>

@endsection
