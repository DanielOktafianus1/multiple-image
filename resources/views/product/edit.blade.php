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

    <title>Document</title>
</head>

<body>
    <div style="max-width: 1700px; margin:auto">
        <nav class="navbar" style="background-color: rgb(9, 9, 60)">
            <div class="container-fluid">
                <a class="navbar-brand text-white" href="#" style="font-weight: 700; font-size:40px">Lo.Go</a>
            </div>
        </nav>

        <form id="uploadForm" action="{{ route('products.update', encrypt($editProduct->id)) }}" method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="productWraper">
                <div class="createProductContainer">
                    <div class="container">
                        <div class="card card-body">

                            @foreach ($errors->all() as $error)
                                @if (Str::contains($error, ['image']))
                                    <i>{{ $error }}</i>
                                @endif
                            @endforeach

                            <div class="w-full mb-3 border border-dashed"
                                style="background-color: rgba(0, 0, 0, 0.047); height:200px; overflow-x:auto">
                                <div id="previewContainer" class="d-flex flex-wrap gap-2 justify-content-start ">


                                    @foreach ($editProduct->imageProduct as $imageProduct)
                                        <img src="{{ asset('/storage/' . $imageProduct->image) }}" alt=""
                                            style="max-width: 150px; max-height:150px">
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="imageInput" class="btn btn-primary col-12 mb-5">
                                    <strong>Add Image</strong>
                                    <input type="file" id="imageInput" class="d-none" multiple accept="image/*">
                                </label>
                            </div>

                            <input type="file" hidden name="image[]" multiple id="finalImageInput" name="image">

                            <div class="row mb-3">
                                <div class="col-4">
                                    <strong>Name Product :</strong>
                                </div>
                                <div class="col-8">
                                    <input type="text" class="form-control" placeholder="Name product"
                                        name="productName"
                                        value="{{ old('productName', $editProduct->productName ?? '') }}">
                                    @error('productName')
                                        <i>{{ $message }}</i>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-4">
                                    <strong>Price :</strong>
                                </div>
                                <div class="col-8">
                                    <input type="number" class="form-control" placeholder="Price" name="price"
                                        min="0" value="{{ old('price', $editProduct->price ?? '') }}">
                                    @error('price')
                                        <i>{{ $message }}</i>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-4">
                                    <strong>Description :</strong>
                                </div>
                                <div class="col-8">
                                    <textarea name="desc" class="form-control" id="" cols="30" rows="5" placeholder="Description">{{ old('desc', $editProduct->desc ?? '') }}</textarea>
                                    @error('desc')
                                        <i>{{ $message }}</i>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="btnControlContainer">
                    <button type="submit" class="btnAddProduct">Update Product</button>
                    <br><br>
                    <a href="{{ route('products.index') }}" class="btnBack">Back</a>
                </div>
            </div>
        </form>
    </div>

    <script>
        let allFiles = [];

        const imageInput = document.getElementById('imageInput');
        const finalImageInput = document.getElementById('finalImageInput');
        const previewContainer = document.getElementById('previewContainer');

        window.existingImages = @json($editProduct->imageProduct);
        console.log(window.existingImages);

        window.addEventListener('DOMContentLoaded', async () => {

            try {
                const filesFromDB = await Promise.all(window.existingImages.map(async (img) => {
                    const resp = await fetch(`/storage/${img.image}`);
                    const blob = await resp.blob();
                    const file = new File([blob], img.image.split('/').pop(), {
                        type: blob.type
                    })
                    return {
                        id: crypto.randomUUID(),
                        file,
                        fromDB: true
                    }

                }));
                allFiles = allFiles.concat(filesFromDB);

                renderPreviews();
            } catch (error) {
                console.error('Error', error);
            }


        });

        imageInput.addEventListener('change', function(event) {

            const newFiles = Array.from(event.target.files).map(file => ({
                file,
                fromDB: false
            }));

            allFiles = allFiles.concat(newFiles);
            renderPreviews();
        });

        function renderPreviews() {

            previewContainer.innerHTML = '';

            allFiles.forEach((item, index) => {
                const reader = new FileReader();

                reader.onload = function(e) {

                    // Add tag div (wraper)
                    const container = document.createElement('div');
                    container.style.position = 'relative';
                    container.style.display = 'inline-block'

                    // Add tag image
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'imgThumbnail';
                    img.style.maxWidth = '150px';
                    img.style.maxHeight = '150px';
                    img.style.margin = '10px';

                    // Button delete
                    const btnDelete = document.createElement('button');
                    btnDelete.innerHTML = 'x';
                    btnDelete.type = 'button';
                    btnDelete.style.position = 'absolute';
                    btnDelete.style.top = '0';
                    btnDelete.style.right = '0';
                    btnDelete.style.color = 'white';
                    btnDelete.style.background = 'red';
                    btnDelete.style.border = 'none';
                    btnDelete.style.borderRadius = '100px';
                    btnDelete.style.width = '20px';
                    btnDelete.style.height = '20px';
                    btnDelete.style.fontSize = '16px';
                    btnDelete.style.margin = '5px';
                    btnDelete.style.display = 'flex';
                    btnDelete.style.justifyContent = 'center';
                    btnDelete.style.alignItems = 'center';

                    btnDelete.addEventListener('click', () => {
                        allFiles.splice(index, 1);
                        renderPreviews()
                    });

                    container.appendChild(img)
                    container.appendChild(btnDelete);
                    previewContainer.appendChild(container)
                }

                reader.readAsDataURL(item.file);

                console.log(item.file);
            });

        }

        document.getElementById('uploadForm').addEventListener('submit', function(e) {

            const dataTranfer = new DataTransfer()
            allFiles.forEach(item => dataTranfer.items.add(item.file));

            finalImageInput.files = dataTranfer.files;

        });
        console.log(finalImageInput.files);
    </script>
</body>

</html>
