<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show()
    {
        $product = Product::all();

        return response()->json([
            'data' => $product,
        ]);
    }

    public function addProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'text' => 'nullable|string',
            'price' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        } else {
            $photoPath = null;
        }

        $newProduct = Product::create([
            'name' => $request->name,
            'text' => $request->text,
            'price' => $request->price,
            'photo' => $photoPath,
        ]);

        return response()->json([
            'data' => $newProduct
        ]);
    }
}
