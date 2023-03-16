<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $categories = Categorie::query()->select('id','name')->get();
       return response()->json(['message'=>'Success','status_code'=>200,'data'=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $name = $request->input('name');
        if($name)
        {
            $categorie = Categorie::query()->create(
                [
           'name'=>$name,
                ]);
        return response()->json(['message'=>'Success','status_code'=>200,'data'=>$categorie]);

        }else
        {
            return response()->json(['message'=>'Parameter <name> should be exist','status_code'=>400]);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Categorie $categorie,string $id)
    {
        
        $cate = Categorie::query()->where('id',$id)->get();
        if(is_null($cate->first()))
            {
              return response()->json(['message'=>'Success','status_code'=>200,'data'=>[]]); 
              #TODO replace [] if it's not correct

            }
        else
            {
                return response()->json(['message'=>'Success','status_code'=>200,'data'=>$cate->first()]);
            }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categorie $categorie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categorie $categorie)
    {
        //
    }
}
