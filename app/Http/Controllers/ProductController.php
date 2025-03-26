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
        // Валидация данных
        $request->validate([
            'name' => 'required|string|max:255',
            'text' => 'nullable|string',
            'price' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Ограничение на изображение
        ]);

        // Обработка фото
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public'); // Сохраняем в storage/app/public/photos
        } else {
            $photoPath = null; // Если фото нет
        }

        // Создание продукта
        $newProduct = Product::create([
            'name' => $request->name,
            'text' => $request->text,
            'price' => $request->price,
            'photo' => $photoPath, // Сохраняем путь к фото в БД
        ]);

        return response()->json([
            'data' => $newProduct
        ]);
    }
}
