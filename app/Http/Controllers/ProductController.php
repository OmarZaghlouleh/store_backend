<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Throwable;

/**
 * Summary of ProductController
 */
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::query()->get();
        return response()->json(['message'=>'success','status_code'=>200,'data'=>$products]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

      try {
        $request->validate([
            'name'=>'required|string',
            'price'=>'required|numeric',
            'description'=>'required|max:255',
            'img_url'=>'required|string',
            'quantity'=>'required|integer',
            'exp_date'=>'required|date',
            'categorie_id'=>'required|integer',
    
           ]);

           $id = $request->input('categorie_id');
           $categorie = Categorie::query()->find($id);
           if($categorie)
           {
            
            $product = Product::query()->create($request->all());
            return response()->json(['message'=>'Created successfuly','status_code'=>200,'data'=>$product]);
           }
           else
           {
            
            throw new Exception("Categorie with id:$id doesn't found");
           }
      } catch (\Throwable $th) {
        return response()->json(['message'=>$th->getMessage(),'status_code'=>400,]);
    }

      

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product,string $id)
    {
        $prod = Product::query()->find($id);
        
        if($prod)
            return response()->json(['message'=>'Success','status_code'=>200,'data'=>$prod]);
        else
            return response()->json(['message'=>"Product with id:$id doesn't found",'status_code'=>400]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product,string $id)
    {
        try {

        $request->validate([
            'name'=>'string',
            'price'=>'numeric',
            'description'=>'string',
            'exp_date'=>'date',
            'img_url'=>'string',
            'categorie_id'=>'integer',
            'quantity'=>'integer',
        ]);
        $product = Product::query()->find($id);
       $categorie=false;
        if($request->has('categorie_id'))
            {
                $categorieId = $request->input('categorie_id');
                $categorie = Categorie::query()->find($categorieId);
            if($categorie)
           {
            
            $product->update($request->all());
            return response()->json(['message'=>'Updated successfuly','status_code'=>200,'data'=>$product]);
           }
           else
           {
            
            throw new Exception("Categorie with id:$categorieId doesn't found");
           }
            }else
            {
                $product->update($request->all());
                return response()->json(['message'=>'Updated successfuly','status_code'=>200,'data'=>$product]);
            }


      } catch (\Throwable $th) {
        return response()->json(['message'=>$th->getMessage(),'status_code'=>400,]);
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product,string $id)
    {
        $prod = Product::query()->find($id);

        if($prod)
        {   
            $prod->delete();
            return response()->json(['message'=>'Deleted successfuly','status_code'=>200,]);
        }else
        {
            return response()->json(['message'=>"Product with id:$id doesn't found",'status_code'=>400,]);
        }
    }

      public function search(Product $product,string $name){
        
        $products = Product::query()->where('name','like','%'.$name.'%')->get();
        return response()->json(['message'=>'Success','status_code'=>200,'data'=>$products]);
    }

  
}

