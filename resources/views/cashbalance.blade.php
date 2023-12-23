@extends('layouts.app')
@section('content')
    <div class="container">
        @php
            //$selectedStore = request()->cookie('selectedStore', '0');
        @endphp
        <form method="GET" action="{{route('cash.balance')}}">
            <div class="row g-2">
                <div class="col-md">
                    <div class="form-floating">
                        <select class="form-select" id="store" name="store">
                            <option selected disabled value="0">--Seleccione una Tienda--</option>
                            <option value="1" {{ $selectedStore == '1' ? 'selected' : '' }}>San Camilo</option>
                            <option value="2" {{ $selectedStore == '2' ? 'selected' : '' }}>Maternos</option>
                            <option value="3" {{ $selectedStore == '3' ? 'selected' : '' }}>Maomas</option>
                            <option value="4" {{ $selectedStore == '4' ? 'selected' : '' }}>Camana</option>
                        </select>
                        <label for="floatingSelectGrid">Tienda:</label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <select class="form-select" id="date" name="date" >
                            <option selected disabled>--Seleccione un rango--</option>
                            <option value="1" {{ $selectedDate == '1' ? 'selected' : '' }}>Hoy</option>
                            <option value="2" {{ $selectedDate == '2' ? 'selected' : '' }}>Semana</option>
                            <option value="3" {{ $selectedDate == '3' ? 'selected' : '' }}>Mensual</option>
                        </select>
                        <label for="floatingSelectGrid">Seleccione la fecha</label>
                    </div>
                </div>
            </div>

        </form>
        <div class="container p-4 my-5 bg-white" >
            @php
                $saleTotal = 0;
                $totalCash = 0;
            @endphp
            @foreach($sale as $s)
                @php
                    $saleTotal += $s->price
                @endphp
            @endforeach
            @foreach($cash as $c)
                @php
                    $totalCash += $c->amount
                @endphp
            @endforeach

            <h2 align="center" class="mb-5">Balance de Caja@php
                    if ($selectedDate == 1) {
                        echo " Diaria";
                    } elseif ($selectedDate == 2) {
                        echo " Semanal";
                    } elseif ($selectedDate == 3) {
                        echo " Mensual";
                    } @endphp </h2>
                <div class="col-md-4 alert alert-dark" role="alert">
                    VENTA TOTAL: S/. {{ $saleTotal }}
                </div>
                <div class="col-md-4 alert alert-dark" role="alert">
                    CAJA TOTAL : S/. {{ $totalCash }}
                </div>
            @if($totalCash == $saleTotal)
                <h2>CUADRE DE CAJA CORRECTO!!!</h2>
                <button class="btn btn-primary" type="button" disabled>REGISTRAR CUADRE</button>
            @endif
            @if($totalCash != $saleTotal)
                <h2>CUADRE DE CAJA INCORRECTO!!!</h2>
            @endif
        </div>
    </div>
    <script>
        document.getElementById('date').addEventListener('change', function() {
            this.form.submit();
        })
        document.getElementById('store').addEventListener('change', function() {
            this.form.submit();
        })
    </script>
@endsection
