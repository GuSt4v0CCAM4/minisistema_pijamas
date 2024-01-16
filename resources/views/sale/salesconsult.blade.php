@extends('layouts.app')
@section('content')
    <div class="container">
        @php
            //$selectedStore = request()->cookie('selectedStore', '0');
        @endphp
        <form method="GET" action="{{route('sales.consult')}}">
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
            <h2 align="center" class="mb-5">Consulta de Ventas</h2>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Producto:</th>
                <th scope="col">Cantidad:</th>
                <th scope="col">Fecha: </th>
                <th scope="col">Vendedor</th>
                <th scope="col">Precio:</th>
            </tr>
            </thead>
            <tbody>
            @php
            $totalPrice = 0;
            @endphp
            @if(isset($datos))
                @foreach($datos as $dato)
                    @php
                        $date = $dato->date;
                        $datetime = new DateTime($date);
                        $fecha = $datetime->format('l j \d\e M. \d\e\l Y');
                        $preciodeventa = $dato->price * $dato->quantity;
                        $totalPrice += $preciodeventa;
                    @endphp
                    <tr>
                        <td>{{ $dato->product }}</td>
                        <td>{{ $dato->quantity }}</td>
                        <td>{{ $fecha }}</td>
                        <td>{{ $dato->name }}</td>
                        <td>S/. {{ $preciodeventa }}</td>

                    </tr>
                @endforeach
                <tr>
                    <td colspan="4"><strong>TOTAL:</strong></td>
                    <td><strong>S/. {{ $totalPrice }}</strong></td>
                </tr>
            @endif
            </tbody>
        </table>
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
