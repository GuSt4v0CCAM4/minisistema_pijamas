@extends("layouts.app")
@section('content')
    <div class="container p-4 my-5 bg-white" >
        <h2 align="center" class="mb-4">Editar Gasto</h2>
            @foreach($datos as $dato)
                @php
                $id = $dato->id_reg;
                @endphp
        <form class="row g-3 needs-validation" method="POST" action="{{route('cash.update', ['id' => $id])}}">
            @csrf
            <div class="form-floating">
                <select class="form-select" name="payment" id="cash" aria-label="Floating label select example">
                    <option disabled value="0">--Seleccione una opción--</option>
                    <option value="1" {{ $dato->payment == '1' ? 'selected' : '' }}>Gastos Operativos</option>
                    <option value="2" {{ $dato->payment == '2' ? 'selected' : '' }}>Gastos de Personal</option>
                    <option value="3" {{ $dato->payment == '3' ? 'selected' : '' }}>Otro</option>
                </select>
                <!---<button type="submit">Mostrar registros</button> -->
                <label for="store">Categoria:</label>
            </div>
            <div class="col-md-4">
                <label for="validationCustomUsername" class="form-label">Monto</label>
                <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend">S/.</span>
                    <input type="number" step="any" min="1"  pattern="^[0-9]" class="form-control" id="validationCustomUsername"
                           value="{{$dato->amount}}" name="amount" aria-describedby="inputGroupPrepend" required>
                    <div class="invalid-feedback">
                        Escriba el Monto
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <label for="validationCustom01" class="form-label">Descripción (opcional):</label>
                <input type="text" class="form-control" id="validationCustom01" name="description" value="{{$dato->description}}">
                <div class="valid-feedback">
                    Elija un producto
                </div>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Editar Gasto</button>
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
