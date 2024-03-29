@extends('layouts.app')
@section('content')
    <div class="container">
    <div class="row">
        <form method="GET" action="{{route('box.register')}}">
            <div class="row g-2">
            <div class="col-md">
            <div class="form-floating">
                <input class="form-control" type="date" name="date" id="date"
                       value="{{ $Date }}" aria-label="Floating label select example">
                <label for="store">Fecha:</label>
            </div>
            </div>
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
            </div>
        </form>
        <div class="col-md-6 mt-5">
            <h2>Registro de Ventas</h2>
            <form class="row g-3 needs-validation" method="POST" action="{{route('box.register.sale')}}">
                @csrf
                <div class="col-md-5">
                    <label for="validationCustom04" class="form-label">Medio de Pago:</label>
                    <select class="form-select" id="validationCustom04" name="payment" required>
                        <option selected disabled value="">Elije una opción</option>
                        <option value="1"> Efectivo</option>
                        <option value="2"> Transferencia</option>
                        <option value="3"> Yape</option>
                        <option value="4"> Plin</option>
                        <option value="7"> Tarjeta Visa</option>
                    </select>
                    <div class="invalid-feedback">
                        Elije un medio de pago
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="sale_price" class="form-label">Precio</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">S/.</span>
                        <input type="number" step="any" min="1"  pattern="^[0-9]" class="form-control" id="sale_price"
                               name="price" aria-describedby="inputGroupPrepend" required>
                        <div class="invalid-feedback">
                            Elija un precio
                        </div>
                    </div>
                </div>


                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Registrar Venta</button>
                </div>
            </form>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Medio:</th>
                    <th scope="col">Fecha: </th>
                    <th scope="col">Vendedor</th>
                    <th scope="col">Valor:</th>
                    <th scope="col">Editar:</th>
                    <th scope="col">Eliminar:</th>
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
                            $id = $dato->id_reg;
                            $medio = "";
                            if ($dato->payment == 1) {
                                $medio = 'Efectivo';
                            } elseif ($dato->payment == 2) {
                                $medio = 'Transferencia';
                            } elseif ($dato->payment == 3) {
                                $medio = 'Yape';
                            } elseif ($dato->payment == 4) {
                                $medio = 'Plin';
                            } elseif ($dato->payment == 7) {
                                $medio = 'Tarjeta Visa';
                            } else {
                                $medio = 'Otro';
                            }
                        @endphp
                        <tr>
                            <td>{{ $medio }}</td>
                            <td>{{ $fecha }}</td>
                            <td>{{ $dato->name }}</td>
                            <td>S/. {{ $preciodeventa }}</td>
                            <td><a type="button" class="btn btn-primary" href="{{route('sales.edit', ['id' => $id])}}">Editar</a></td>
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
            @if(session('success_edit_s'))
                <div class="col-md-4 alert alert-success">
                    {{ session('success_edit_s') }}
                </div>
            @endif
            @if(session('error_edit_s'))
                <div class="col-md-4 alert alert-success">
                    {{ session('error_edit_s') }}
                </div>
            @endif
            @if(session('success_s'))
                <div class="col-md-4 alert alert-success">
                    {{ session('success_s') }}
                </div>
            @endif
            @if(session('error_s'))
                <div class="col-md-4 alert alert-success">
                    {{ session('error_s') }}
                </div>
            @endif
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
                                    <a type="button" class="btn btn-danger" href="{{route('sales.delete', ['id' => $id])}}">Si, ELIMINAR</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Columna de Gastos -->
        <div class="col-md-6 mt-5">
            <h2>Registro de Gastos</h2>
            <form class="row g-3 needs-validation" method="POST" action="{{route('box.register.cash')}}">
                @csrf
                <div class="col-md-5">
                    <label for="validationCustom04" class="form-label">Medio:</label>
                    <select class="form-select" id="validationCustom04" name="payment" required>
                        <option selected disabled value="">Elije una opción</option>
                        <option value="1"> Efectivo</option>
                        <option value="2"> Transferencia</option>
                        <option value="3"> Yape</option>
                        <option value="4"> Plin</option>
                        <option value="7"> Tarjeta Visa</option>
                    </select>
                    <div class="invalid-feedback">
                        Elije un medio de pago
                    </div>
                </div>
                <div class="col md-5">
                    <label for="cash" class="form-label">Tipo:</label>
                    <select class="form-select" id="cash" name="cash" required>
                        <option selected disabled value="0">--Seleccione una opción--</option>
                        <option value="Gastos Operativos" >Gastos Operativos</option>
                        <option value="Gastos de Personal" >Gastos de Personal</option>
                        <option value="Otro" >Otro</option>
                    </select>
                    <div class="invalid-feedback">
                        Elije un medio de pago
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="validationCustomUsername" class="form-label">Monto</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">S/.</span>
                        <input type="number" step="any" min="1"  pattern="^[0-9]" class="form-control" id="validationCustomUsername"
                               name="amount" aria-describedby="inputGroupPrepend" required>
                        <div class="invalid-feedback">
                            Escriba el Monto
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Registrar Gasto</button>
                </div>
            </form>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Medio:</th>
                    <th scope="col">Descripcion:</th>
                    <th scope="col">Fecha: </th>
                    <th scope="col">Monto:</th>
                    <th scope="col">Editar</th>
                    <th scope="col">Editar</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $totalPrice2 = 0;
                @endphp
                @if(isset($datos2))
                    @foreach($datos2 as $dato)
                        @php
                            $date2 = $dato->date;
                            $datetime2 = new DateTime($date2);
                            $fecha2 = $datetime2->format('l j \d\e M. \d\e\l Y');
                            $totalPrice2 += $dato->amount;
                            $payment2 = $dato->payment;
                            if ($payment2 == 1) {
                                $payment2 = 'Efectivo';
                            } elseif ($payment2 == 2) {
                                $payment2 = 'Transferencia';
                            } elseif ($payment2 == 3) {
                                $payment2 = 'Yape';
                            } elseif ($payment2 == 4) {
                                $payment2 = 'Plin';
                            } elseif ($payment2 == 7) {
                                $payment2 = 'Tarjeta Visa';
                            } else {
                                $payment2 = 'Otro';
                            }
                            $id2 = $dato->id_reg
                        @endphp
                        <tr>
                            <td>{{ $payment2 }}</td>
                            <td>{{ $dato->description }}</td>
                            <td>{{ $fecha2 }}</td>
                            <td>S/. {{ $dato->amount }}</td>
                            <td><a type="button" class="btn btn-primary" href="{{route('cash.edit', ['id' => $id2])}}">Editar</a></td>
                            <td><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal{{$id2}}">
                                    Eliminar
                                </button>
                            </td>

                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3"><strong>TOTAL:</strong></td>
                        <td><strong>S/. {{ $totalPrice2 }}</strong></td>
                    </tr>
                @endif
                </tbody>
            </table>
            @php
            $ganancias = $totalPrice - $totalPrice2;
            @endphp
            @if(isset($datos2))
                @foreach($datos2 as $item)
                    @php
                        $id2 = $item->id_reg;
                    @endphp
                        <!-- Modal -->
                    <div class="modal fade" id="modal{{$id2}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    <a type="button" class="btn btn-danger" href="{{route('cash.delete', ['id' => $id2])}}">Si, ELIMINAR</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div><h3>Ganacias: S/.{{$ganancias}}</h3><button class="btn btn-primary" type="button" onclick="window.location.href='{{route('cash.close')}}'">REGISTRAR CUADRE</button></div><h3>NO TE OLVIDES DE REGISTRAR CAJA</h3>
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
        @if(session('danger'))
            <div class="col-md-4 alert alert-danger">
                {{ session('danger') }}
            </div>
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
