@extends('layouts.app')
@section('content')
    <div class="container">
        @php
            //$selectedStore = request()->cookie('selectedStore', '0');
        @endphp
        <form method="GET" action="{{route('cash.consult')}}">
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
            <h2 align="center" class="mb-5">Consulta de Gastos </h2>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Categoria:</th>
                    <th scope="col">Descripcion:</th>
                    <th scope="col">Fecha: </th>
                    <th scope="col">Monto:</th>
                    <th scope="col">Editar</th>
                    <th scope="col">Editar</th>
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
                            $totalPrice += $dato->amount;
                            $payment = $dato->payment;
                            if ($payment == 1) {
                                $payment = 'Gastos Operativos';
                            } elseif ($payment == 2) {
                                $payment = 'Gastos de Personal';
                            } elseif ($payment == 3) {
                                $payment = 'Otro';
                            }
                            $id = $dato->id_reg
                        @endphp
                        <tr>
                            <td>{{ $payment }}</td>
                            <td>{{ $dato->description }}</td>
                            <td>{{ $fecha }}</td>
                            <td>S/. {{ $dato->amount }}</td>
                            <td><a type="button" class="btn btn-primary" href="{{route('cash.edit', ['id' => $id])}}">Editar</a></td>
                            <td><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal{{$id}}">
                                    Eliminar
                                </button>
                            </td>

                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3"><strong>TOTAL:</strong></td>
                        <td><strong>S/. {{ $totalPrice }}</strong></td>
                    </tr>
                @endif
                </tbody>
            </table>
            @if(isset($datos))
                @foreach($datos as $item)
                    @php
                        $id = $item->id_reg;
                    @endphp
                        <!-- Modal -->
                    <div class="modal fade" id="modal{{$id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar esta venta?</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Esta seguro que quiere eliminar el registro de esta venta? No se podra recuperar.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <a type="button" class="btn btn-danger" href="{{route('cash.delete', ['id' => $id])}}">Si, ELIMINAR</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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
