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
                        <select class="form-select" id="date" name="date" >
                            <option selected disabled>--Seleccione un rango--</option>
                            <option value="1" {{ $selectedDate == '1' ? 'selected' : '' }}>Hoy</option>
                            <option value="2" {{ $selectedDate == '2' ? 'selected' : '' }}>Semana</option>
                            <option value="3" {{ $selectedDate == '3' ? 'selected' : '' }}>Mensual</option>
                            <option value="4" {{ $selectedDate == '4' ? 'selected' : '' }}>Personalizado</option>

                        </select>
                        <label for="date">Seleccione la fecha</label>
                        <div id="customRange" style="display: none;">
                            <div class="col-md">
                                <div class="form-floating">
                                     <input class="form-control" type="date" value="" id="startDate" name="startDate"
                                           aria-label="Floating label select example">
                                    <label for="startDate">Inicio:</label>
                                </div>
                            </div>

                            <div class="col-md">
                                <div class="form-floating">
                                       <input class="form-control" type="date" value="" id="endDate" name="endDate"
                                           aria-label="Floating label select example">
                                    <label for="endDate">Fin:</label>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit">Consultar</button>
                        </div>
                    </div>

                </div>

                <input type="hidden" id="startDateHidden" name="startDate">
                <input type="hidden" id="endDateHidden" name="endDate">
            </div>

        </form>
        <div class="container p-4 my-5 bg-white" >

            @php
                $saleTotal = [0.0,0.0,0.0,0.0];
                $totalCash = [0.0,0.0,0.0,0.0];
            @endphp
            @if(isset($sale))
                @foreach($sale as $s)
                    @php
                    if ($s->id_store == 1) {
                        $saleTotal[0] += $s->total;
                    }elseif ($s->id_store == 2) {
                        $saleTotal[1] += $s->total;
                    }elseif ($s->id_store == 3) {
                        $saleTotal[2] += $s->total;
                    }elseif ($s->id_store == 4) {
                        $saleTotal[3] += $s->total;
                    }

                    @endphp
                @endforeach
                @foreach($cash as $c)
                    @php
                    if ($c->id_store == 1) {
                        $totalCash[0] += $c->total;
                    } elseif ($c->id_store == 2) {
                        $totalCash[1] += $c->total;
                    } elseif ($c->id_store == 3) {
                        $totalCash[2] += $c->total;
                    } elseif ($c->id_store == 4) {
                        $totalCash[3] += $c->total;
                    }
                    @endphp
                @endforeach
            @endif
            @php
            $profit = [0.0,0.0,0.0,0.0];
            $etiquetas = ['San Camilo', 'Maternos', 'Maomas', 'Camana'];
                for ($i = 0; $i < 4; $i++) {
                    $profit[$i] = $saleTotal[$i] - $totalCash[$i];
                }
            @endphp

            <h2 align="center" class="mb-5">Balance de Caja@php
                    if ($selectedDate == 1) {
                        echo " Diaria";
                    } elseif ($selectedDate == 2) {
                        echo " Semanal";
                    } elseif ($selectedDate == 3) {
                        echo " Mensual";
                    } @endphp </h2>
            <div style="width: 250px; margin: auto;">
                <canvas id="myChart"></canvas>
            </div>
            <br>
            <div class="container text-center">
                <div class="row align-items-start">
                    <div class="col alert alert-secondary" role="alert">
                        San Camilo:
                        <div class="col ">
                             S/. {{ $profit[0] }}
                        </div>
                    </div>
                    <div class="col alert alert-secondary" role="alert">
                        Maternos
                        <div class="col ">
                            S/. {{ $profit[1] }}
                        </div>
                    </div>


                </div>
                <div class="row align-items-start">
                <div class="col alert alert-secondary" role="alert">
                    Maomas:
                    <div class="col ">
                        S/. {{ $profit[2] }}
                    </div>
                </div>
                <div class="col alert alert-primary" role="alert">
                    Camana:
                    <div class="col ">
                        S/. {{ $profit[3]}}
                    </div>
                </div>
                </div>
            </div>


        </div>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Tienda</th>
                <th scope="col">Fecha</th>
                <th scope="col">Efectivo</th>
                <th scope="col">Yape</th>
                <th scope="col">Plin</th>
                <th scope="col">Tranferencia</th>
                <th scope="col">Tarjeta Visa</th>
                <th scope="col">Total</th>
            </tr>
            </thead>
            <tbody>
            @php
                $totalGanancia = [0.0,0.0,0.0,0.0,0.0,0.0];
            @endphp
            @if(isset($tablecash))
                @foreach($tablecash as $t)
                    @php
                    $store = "";
                    if ($t->id_store == 1) {
                        $store = "San Camilo";
                    } elseif ($t->id_store == 2) {
                        $store = "Maternos";
                    } elseif ($t->id_store == 3) {
                        $store = "Maomas";
                    } elseif ($t->id_store == 4) {
                        $store = "Camana";
                    }
                    $payment = $t->payment;
                    $arraypayment = explode('|', $payment);

                    for ($i = 0; $i < 5; $i++) {
                        $totalGanancia[$i] += $arraypayment[$i];
                    }
                    $totalGanancia[5] += $t->total;
                    @endphp
                    <tr>
                        <th scope="row">{{ $store }}</th>
                        <td>{{ $t->date }}</td>
                        <td> S/.{{ $arraypayment[0] }}</td>
                        <td> S/.{{ $arraypayment[1] }}</td>
                        <td> S/.{{ $arraypayment[2] }}</td>
                        <td> S/.{{ $arraypayment[3] }}</td>
                        <td> S/.{{ $arraypayment[4] }}</td>
                        <td> S/.{{ $t->total }}</td>
                @endforeach
            @endif
            <tr>
                <td colspan="2"><strong>TOTAL:</strong></td>
                <td><strong>S/. {{ $totalGanancia[0] }}</strong></td>
                <td><strong>S/. {{ $totalGanancia[1] }}</strong></td>
                <td><strong>S/. {{ $totalGanancia[2] }}</strong></td>
                <td><strong>S/. {{ $totalGanancia[3] }}</strong></td>
                <td><strong>S/. {{ $totalGanancia[4] }}</strong></td>
                <td><strong>S/. {{ $totalGanancia[5] }}</strong></td>
            </tr>
            </tbody>
        </table>
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

    <script>
        var etiqueta = @json($etiquetas);
        var ganancias = @json($profit);

        if (document.getElementById('date').value === '4') {
            document.getElementById('customRange').style.display = 'flex';
        }
        document.getElementById('date').addEventListener('change', function() {

            if (this.value === '4') {
                document.getElementById('customRange').style.display = 'flex';
            } else {
                document.getElementById('customRange').style.display = 'none';
                this.form.submit();
            }

        });

        document.getElementById('store').addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('customRange').addEventListener('change', function () {
            // Obtener los valores de startDate y endDate
            var startDateValue = document.getElementById('startDate').value;
            var endDateValue = document.getElementById('endDate').value;

            // Asignar los valores a campos ocultos en el formulario
            document.getElementById('startDateHidden').value = startDateValue;
            document.getElementById('endDateHidden').value = endDateValue;

            // Enviar el formulario
            this.form.submit();
        });



        // Muestra u oculta el campo de rango de fechas según la selección del usuari
    </script>
    <script src="{{asset('js/app.js')}}"></script>
@endsection
