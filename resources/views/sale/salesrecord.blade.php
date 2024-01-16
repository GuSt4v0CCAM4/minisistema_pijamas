@extends('layouts.app')
@section('content')
<div class="container">

    <form method="GET" action="{{route('sales.record')}}">
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
        <h2 align="center" class="mb-5">Registrar Venta</h2>
        <form class="row g-3 needs-validation" method="POST" action="{{route('sales.record.store')}}">
            @csrf
        <div class="col-md-8">
            <label for="product" class="form-label">Producto (ID):</label>
            <input type="text" class="form-control" id="product" value="" name="product" required>
            <div class="valid-feedback">
                Elija un producto
            </div>
            <div id="productList" class="list-group" style="position: absolute; z-index: 1000;"></div>
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
        <div class="col-md-3">
            <label for="validationCustom05" class="form-label">Cantidad:</label>
            <input type="number" min="1"  pattern="^[0-9]" class="form-control" id="validationCustom05"
                   name="quantity" value="1" required>
            <div class="invalid-feedback">
                Elija una cantidad
            </div>
        </div>
            <div class="col-md-3">
                <label for="validationCustom04" class="form-label">Medio de Pago:</label>
                <select class="form-select" id="validationCustom04" name="payment" required>
                    <option selected disabled value="">Elije una opción</option>
                    <option value="1"> Efectivo</option>
                    <option value="2"> Transferencia</option>
                    <option value="3"> Yape</option>
                    <option value="4"> Plin</option>
                    <option value="7"> Otro</option>
                </select>
                <div class="invalid-feedback">
                    Elije un medio de pago
                </div>
            </div>

            <div class="col-12">
            <button class="btn btn-primary" type="submit">Registrar Venta</button>
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
    </div><!-- Live as if you were to die tomorrow. Learn as if you were to live forever. - Mahatma Gandhi -->
</div>
    <script>
        document.getElementById('store').addEventListener('change', function() {
            this.form.submit();
        })
        $(document).ready(function(){
            $("#product").keyup(function(){
                var value = $(this).val();
                $.ajax({
                    url: "{{route('get.products')}}",
                    method: "GET",
                    data: {searchTerm: value},
                    success: function (response){
                        var products = response;
                        var list = "";
                        for(var i = 0; i < products.length; i++){
                            list += "<a href='#' class='list-group-item list-group-item-action product-item' data-id_product='" + products[i].id_product + "'>" + products[i].id_product + "</a>";
                        }
                        $("#productList").html(list);
                    }
                });
            });
            $(document).on('click', '.product-item', function(e){
                e.preventDefault();
                var id_product = $(this).data('id_product');
                $("#product").val(id_product);

                $.ajax({
                    url: "{{route('get.product.details')}}",
                    method: "GET",
                    data: {id_product: id_product},
                    success: function (response){
                        $("#sale_price").val(response);
                    }
                });
            });
        });

    </script>
@endsection
