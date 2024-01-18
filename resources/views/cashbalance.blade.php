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
            @if(isset($sale))
                @foreach($sale as $s)
                    @php
                        $saleTotal += ($s->price * $s->quantity)
                    @endphp
                @endforeach
                @foreach($cash as $c)
                    @php
                        $totalCash += $c->amount
                    @endphp
                @endforeach
            @endif


            <h2 align="center" class="mb-5">Balance de Caja@php
                    if ($selectedDate == 1) {
                        echo " Diaria";
                    } elseif ($selectedDate == 2) {
                        echo " Semanal";
                    } elseif ($selectedDate == 3) {
                        echo " Mensual";
                    } @endphp </h2>
            <div class="container text-center">
                <div class="row align-items-start">
                    <div class="col alert alert-secondary" role="alert">
                        Venta total:
                        <div class="col ">
                             S/. {{ $saleTotal }}
                        </div>
                    </div>

                    <div class="col alert alert-secondary" role="alert">
                        Gasto Total:
                        <div class="col ">
                            S/. {{ $totalCash }}
                        </div>
                    </div>
                    <div class="col alert alert-primary" role="alert">
                        Ganancia Total:
                        <div class="col ">
                            @php
                                $ganancia = $saleTotal - $totalCash
                            @endphp
                            S/. {{ $ganancia }}
                        </div>
                    </div>
                </div>
            </div>


                <button class="btn btn-primary" type="button" onclick="window.location.href='{{route('closeboxregister', ['saleTotal' => $saleTotal, 'cashTotal' => $totalCash, 'profit' => $ganancia])}}'">REGISTRAR CUADRE</button>
        </div>
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
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Tienda:</th>
                    <th scope="col">Ventas:</th>
                    <th scope="col">Gastos: </th>
                    <th scope="col">Ganancia:</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $totalganancia = 0;
                @endphp
                @if(isset($tablecash))
                    @foreach($tablecash as $item)
                        @php
                            $date = $item->date;
                            $datetime2 = new DateTime($date);
                            $fecha2 = $datetime2->format('l j \d\e M. \d\e\l Y');
                            $totalganancia += $item->profit;
                            $tienda = $item->id_store;
                            if ($tienda== 1) {
                                $tienda = 'San Camilo';
                            } elseif ($tienda== 2) {
                                $tienda= 'Maternos';
                            } elseif ($tienda == 3) {
                                $tienda = 'Maomas';
                            } elseif ($tienda == 4) {
                                $tienda = 'Camana';
                            }
                            //$id2 = $dato->id_reg
                        @endphp
                        <tr>
                            <td>{{ $tienda }}</td>
                            <td>S/. {{ $item->sale}}</td>
                            <td>S/. {{ $item->spent }}</td>
                            <td>S/. {{ $item->profit }}</td>

                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3"><strong>TOTAL:</strong></td>
                        <td><strong>S/. {{ $totalganancia }}</strong></td>
                    </tr>
                @endif
                </tbody>
            </table>
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
