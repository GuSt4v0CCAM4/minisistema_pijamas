@extends('layouts.app')
@section('content')
    <div class="container">
        @php
            //$selectedStore = request()->cookie('selectedStore', '0');
        @endphp
        <form method="GET" action="{{route('workerranking')}}">
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
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Nombre</th>
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
                            $total = 0;
                            $total += $t->total;
                        @endphp
                        <tr>
                            <th scope="row">{{ $t->name }}</th>
                            <td> S/.{{ $t->total }}</td>
                    @endforeach
                @endif
                <tr>
                    <td colspan="1"><strong>TOTAL:</strong></td>
                    @if(isset($total))<td><strong>S/. {{ $total}}</strong></td> @endif
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script>
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
    </script>
@endsection
