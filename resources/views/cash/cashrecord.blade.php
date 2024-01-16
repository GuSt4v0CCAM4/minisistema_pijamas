@extends('layouts.app')
@section('content')
    <div class="container">

        <form method="GET" action="{{route('cash.record')}}">
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
            <h2 align="center" class="mb-4">Registrar Caja</h2>
            <div class="col-md-4 mb-4">
                <form method="GET" action="{{route('cash.record')}}">
                    <div class="form-floating">
                        <select class="form-select" name="cash" id="cash" aria-label="Floating label select example">
                            <option selected disabled value="0">--Seleccione una opción--</option>
                            <option value="1" {{ $selectedCash == '1' ? 'selected' : '' }}>Efectivo</option>
                            <option value="2" {{ $selectedCash == '2' ? 'selected' : '' }}>Transferencias</option>
                            <option value="3" {{ $selectedCash == '3' ? 'selected' : '' }}>Yape</option>
                            <option value="4" {{ $selectedCash == '4' ? 'selected' : '' }}>Plin</option>
                            <option value="5" {{ $selectedCash == '5' ? 'selected' : '' }}>Visa</option>
                            <option value="6" {{ $selectedCash == '6' ? 'selected' : '' }}>Gasto</option>
                            <option value="7" {{ $selectedCash == '7' ? 'selected' : '' }}>Otro</option>
                        </select>
                        <!---<button type="submit">Mostrar registros</button> -->
                        <label for="store">Categoria:</label>
                    </div>
                </form>
            </div>
            <div class="mb-6">
            </div>

            <form class="row g-3 needs-validation" method="POST" action="{{route('cash.record.store')}}">
                @csrf
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
                <div class="col-md-8">
                    <label for="validationCustom01" class="form-label">Observaciones (opcional):</label>
                    <input type="text" class="form-control" id="validationCustom01" value="" name="description">
                    <div class="valid-feedback">
                        Elija un producto
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Registrar Caja</button>
                </div>
            </form>
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

    </div>
    <script>
        document.getElementById('store').addEventListener('change', function() {
            this.form.submit();
        })
        document.getElementById('cash').addEventListener('change', function() {
            this.form.submit();
        })
        document.getElementById('spent').addEventListener('change', function() {
            this.form.submit();
        })
    </script>
@endsection
