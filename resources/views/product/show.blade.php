<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ asset('css/index.css') }}">

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">


    <title>Document</title>
</head>

<body>
    <div style="max-width: 1700px; margin:auto">
        <nav class="navbar" style="background-color: rgb(9, 9, 60)">
            <div class="container-fluid">
                <a class="navbar-brand text-white" href="#" style="font-weight: 700; font-size:40px">Lo.Go</a>
            </div>
        </nav>

        <div class="productWraper">
            <div class="productContainer">
                <div class="row">
                    <div class="col-4">
                        @foreach ($product->imageProduct as $imageProduct)
                            <div style="margin: auto">
                                <img src="{{ asset('/storage/' . $imageProduct->image) }}" alt=""
                                    style="max-width: 150px; max-height:150px; margin-bottom:10px">
                            </div>
                        @endforeach
                    </div>
                    <div class="col-8 ">
                        <h1>{{ $product->productName }}</h1>
                        <h3 class="text-success mb-4">{{ number_format($product->price, 0, ',', '.') }}</h3>
                        <p>{!! nl2br($product->desc) !!}</p>
                    </div>
                </div>
            </div>
            <div class="btnControlContainer">
                <a href="{{ route('products.edit', encrypt($product->id)) }}" class="btnAddProduct">Edit Product</a>
                <br><br><br>
                <a href="{{ route('products.index') }}" class="btnBack">Back</a>
            </div>
        </div>
    </div>
</body>

</html>
