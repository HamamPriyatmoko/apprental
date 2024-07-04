<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(){
        $products = Product::all();
        return response()->json($products);
    }

    public function show($id){
        $product = Product::find($id);
        return response()->json(['message' => 'Success','data' => $product]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'merk' => 'required|string|max:255',
            'harga' => 'required|string',
            'imageUrl' => 'required|url',
        ]);

        $product = new Product();
        $product->nama = $request->nama;
        $product->deskripsi = $request->deskripsi;
        $product->jenis = $request->merk; // Merk disesuaikan dengan jenis
        $product->harga = $request->harga;
        $product->gambar = $request->imageUrl; // Menggunakan imageUrl dari Flutter
        $product->save();

        return response()->json(['message' => 'Produk berhasil ditambahkan'], 200);
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $filename, 'public');

            // Mengembalikan URL yang benar untuk gambar
            $url = url('storage/' . $filePath);
            $url = str_replace('127.0.0.1', '10.0.2.2', $url); // Sesuaikan URL jika perlu

            return response()->json(['url' => $url], 200);
        } else {
            return response()->json(['error' => 'No file uploaded'], 400);
        }
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->nama = $request->input('nama');
        $product->deskripsi = $request->input('deskripsi');
        $product->jenis = $request->input('jenis');
        $product->harga = $request->input('harga');
        $product->gambar = $request->input('gambar'); // jika menggunakan gambar
        $product->save();

        return response()->json($product, 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Check authorization before deleting
        if (!auth()->admin()->can('delete', $product)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $product->delete();
            return response()->json(['message' => 'Product deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete product', 'error' => $e->getMessage()], 500);
        }
    }
}
