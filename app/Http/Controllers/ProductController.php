<?php

namespace App\Http\Controllers;

use App\Models\ImageProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['imageProduct' => function ($e) {
            $e->orderBy('id', 'desc')->limit(1);
        }])
            ->orderBy('id', 'desc')
            ->get();
        return view('product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|array',
            'image.*' => 'image|mimes:png,jpg,jpeg,gif,svg|max:2084',

            'productName' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'desc' => 'required|string',
        ]);

        // $product = Product::create([
        //     'productName' => $request->productName,
        //     'price' => $request->price,
        //     'desc' => $request->desc,
        // ]);

        $product = Product::create($request->all());

        if ($request->hasFile('image')) {
            $imagePaths = [];

            foreach ($request->file('image') as $key => $image) {

                $imagePaths[] = $image->store('imageProduct', 'public');
            }

            foreach ($imagePaths as $key => $images) {
                ImageProduct::create([
                    'image' => $images,
                    'idProduct' => $product->id,
                ]);
            }
        }

        if ($product) {
            return redirect()->route('products.index')->with('createProduct', 'Succesfuly added new products.');
        }

        return redirect()->back()->withInput()->with('failToAdd', 'Failed to add new products');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = decrypt($id);

        $product = Product::with(['imageProduct' => function ($e) {
            $e->orderBy('id');
        }])
            ->findOrFail($id);
        return view('product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = decrypt($id);

        $editProduct = Product::with(['imageProduct' => function ($e) {
            $e->orderBy('id');
        }])
            ->findOrFail($id);

        return view('product.edit', compact('editProduct'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $id = decrypt($id);
        $request->validate([
            'image' => 'required|array',
            'image.*' => 'image|mimes:png,jpg,jpeg,gif,svg|max:2084',

            'productName' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'desc' => 'required|string',
        ]);

        $product = Product::findOrFail($id);

        $updateProduct = $product->update([
            'productName' => $request->productName,
            'price' => $request->price,
            'desc' => $request->desc
        ]);

        if ($request->hasFile('image')) {

            $imagePaths = [];

            ImageProduct::where('idProduct', $id)->delete();

            foreach ($request->file('image') as $key => $image) {
                $imagePaths[] = $image->store('imageProduct', 'public');
            }

            foreach ($imagePaths as $key => $images) {

                $oldImages = ImageProduct::where('idProduct', $product->id);
                $oldImages->create([
                    'image' => $images,
                    'idProduct' => $product->id
                ]);
            }
        }

        if ($updateProduct) {
            return redirect()->route('products.index')->with('updateProduct', 'Successfuly updated product.');
        }

        return redirect()->back()->with('failToUpdate', 'Failed to update product')->withInput();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $id = decrypt($id);

        ImageProduct::where('idProduct', $id)->delete();

        $product = Product::findOrfail($id);

        $product->delete();

        return redirect()->back()->with('deleteProduct', 'Successfuly deleted product');
    }
}
