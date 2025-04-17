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


                @if ($products->isEmpty())
                    <div style="text-align: center; color:rgb(172, 170, 170)">
                        <h2>{{ 'THERE IS NO PRODUCT YET!' }}</h2>
                        <p>{{ 'Please add the product you want to sell' }}</p>
                    </div>
                @else
                    @foreach ($products as $product)
                        <div class="productCard">

                            <div class="ellipsis" onclick="ellipsisActions(this)">
                                <i class="bi bi-three-dots-vertical"></i>
                            </div>

                            <div class="actions containerActions d-none">
                                <a href="{{ route('products.edit', encrypt($product->id)) }}">Edit</a>
                                <form action="{{ route('products.destroy', encrypt($product->id)) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">
                                        Delete
                                    </button>
                                </form>
                            </div>


                            <a href="{{ route('products.show', encrypt($product->id)) }}"
                                style="text-decoration: none; color:black">
                                <div class="imgContainer">
                                    <img src="{{ asset('/storage/' . $product->imageProduct->first()->image) }}"
                                        alt="" class="imgProduct">
                                </div>
                                <div class="textContainer">
                                    <h5 id="nameProduct">{{ $product->productName }}</h5>
                                    <p id="decsProduct">{{ $product->desc }}</p>
                                    <div>{{ number_format($product->price, 0, ',', '.') }}</div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif

            </div>
            <div class="btnControlContainer">
                <a href="{{ route('products.create') }}" class="btnAddProduct">Add Product</a>
            </div>
        </div>
    </div>

    <script>
        function ellipsisActions(e) {

            document.querySelectorAll('.productCard .containerActions').forEach(eHide => {

                if (eHide !== e.closest('.productCard').querySelector('.containerActions')) {
                    eHide.classList.add('d-none');
                }

            });

            const reletedComponent = e.closest('.productCard').querySelector('.containerActions');

            if (reletedComponent) {
                reletedComponent.classList.toggle('d-none')
            }
        }
    </script>
</body>

</html>
