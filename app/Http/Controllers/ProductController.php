<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::get();

        return response()->json([
            'success'     => true,
            'status_code' => 200,
            'data'        => $products
        ]);

    }

    public function show($id)
    {
        $product = Product::find($id);
        if (is_null($product)) {

            return response()->json([
                'success'     => true,
                'status_code' => 200,
                'message'     => "Product with id $id doesn't found."
            ]);

        } else {

            return response()->json([
                'success'     => false,
                'status_code' => 404,
                'data'        => $product
            ]);

        }
    }

    public function store(Request $request)
    {

        $validation = Validator::make($request->only('name', 'description', 'price', 'img', 'category_id'), [
            'name'        => ['required', 'between:2,255', 'unique:products,name', 'string'],
            'description' => ['nullable', 'between:10,5000', 'string'],
            'price'       => ['required', 'numeric'],
            'img'         => ['required', 'string'],
            'category_id' => ['integer', 'required']
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success'     => false,
                'status_code' => 400,
                'message'     => $validation->errors()->first()
            ]);

        } else {

            if (Category::find($request->category_id)) {
                $product = Product::create([
                    'name'        => $request->name,
                    'description' => $request->description,
                    'price'       => $request->price,
                    'img'         => $request->img,
                    'category_id' => $request->category_id
                ]);
                return response()->json([
                    'success'     => true,
                    'status_code' => 200,
                    'data'        => $product
                ]);

            } else {
                return response()->json(['success' => false, 'status_code' => 404, 'message' => "Category with id $request->category_id doesn't found"]);

            }
        }
    }
    public function update(Request $request, $id)
    {

        $validation = Validator::make($request->only('name', 'description', 'price', 'img', 'category_id'), [
            'name'        => ['nullable', 'between:2,255', 'unique:products,name', 'string'],
            'description' => ['nullable', 'between:10,5000', 'string'],
            'price'       => ['nullable', 'numeric'],
            'img'         => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer']

        ]);

        if ($validation->fails()) {
            return response()->json([
                'success'     => false,
                'status_code' => 400,
                'message'     => $validation->errors()->first()
            ]);

        } else {
            $product = Product::find($id);
            if (is_null($product)) {
                return response()->json([
                    'success'     => false,
                    'status_code' => 404,
                    'message'     => "Product with id $id doesn't found."
                ]);

            } else {
                if (Category::find($request->category_id)) {
                    $product->update([
                        'name'        => $request->name,
                        'description' => $request->description,
                        'price'       => $request->price,
                        'img'         => $request->img,
                        'category_id' => $request->category_id
                    ]);
                    return response()->json([
                        'success'     => true,
                        'status_code' => 200,
                        'data'        => $product
                    ]);
                } else {
                    return response()->json(['success' => false, 'status_code' => 404, 'message' => "Category with id $request->category_id doesn't found"]);

                }
            }
        }
    }

    public function search(Request $request)
    {

        $products = Product::where('name', 'like', '%' . $request->name . '%')->get();

        if (!is_null($request->priceFrom) && !is_null($request->priceTo))
            $products = $products
                ->whereBetween('price', [$request->priceFrom, $request->priceTo]);
        ;
        return response()->json([
            'success'     => true,
            'status_code' => 200,
            'data'        => $products
        ]);

    }
    public function delete($id)
    {

        $product = Product::find($id);

        if (!is_null($product)) {

            if ($product->destroy($id)) {
                return response()->json([
                    'success'     => true,
                    'status_code' => 200,
                    'message'     => "Deleted successfully."
                ]);
            } else {
                return response()->json([
                    'success'     => true,
                    'status_code' => 400,
                    'message'     => "Some thing went wrong."
                ]);
            }
        } else {
            return response()->json([
                'success'     => false,
                'status_code' => 404,
                'message'     => "Product with id $id doesn't found."
            ]);
        }

    }
}