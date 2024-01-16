@extends('layouts.app')
@section('content')
    <div class="container">
        @php
            //$selectedStore = request()->cookie('selectedStore', '0');
        @endphp
        <form method="GET" action="{{route('inventory.consult')}}">
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
            </div>

        </form>
        <div class="container p-4 my-5 bg-white" >
            <h2 align="center" class="mb-5">Inventario</h2>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Codigo:</th>
                    <th scope="col">Descripcion: </th>
                    <th scope="col">Marca:</th>
                    <th scope="col">Talla:</th>
                    <th scope="col">Color:</th>
                    <th scope="col">Precio:</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $totalPrice = 0;
                @endphp
                @if(isset($datos))
                    @foreach($datos as $dato)
                        <tr>
                            <td>{{ $dato->id_product }}</td>
                            <td>{{ $dato->description }}</td>
                            <td>{{ $dato->brand }}</td>
                            <td>{{ $dato->size }}</td>
                            <td> {{ $dato->color }}</td>
                            <td>S/. {{ $dato->sale_price }}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.getElementById('store').addEventListener('change', function() {
            this.form.submit();
        })
    </script>
@endsection
