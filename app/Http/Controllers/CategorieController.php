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

       try {
        $request->validate(['name'=>'required|string']); 
        $name = $request->input('name');
        $categorie = Categorie::query()->create($request->all()); 
        return response()->json(['message'=>'Success','status_code'=>200,'data'=>$categorie]);
       } catch (\Throwable $th) {
        return response()->json(['message'=>'Parameter <name> should be exist','status_code'=>400]);

       }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Categorie $categorie,string $id)
    {
        
        $cate = Categorie::query()->where('id',$id)->get()->first();
        if(is_null($cate))
            {
              return response()->json(['message'=>"Failed, categorie with id $id doesn't found",'status_code'=>400,'data'=>[]]); 
              #TODO replace [] if it's not correct
            }
        else
            {
                return response()->json(['message'=>'Success','status_code'=>200,'data'=>$cate]);
            }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categorie $categorie,string $id)
    {
        $name = request()->input('name');
        if($name)
        {
            $newName = $request->input('name');
            $cate = Categorie::query()->find($id);
            $status =false;
            if($cate)
                $status = $cate->update(['name'=>$newName]);
         
            if($status)
            {
                
                return response()->json(['message'=>'Success','status_code'=>200,'data'=>$cate]);
            }
            else
            {              
                return response()->json(['message'=>"Failed, categorie with id $id doesn't found",
                'status_code'=>400,'data'=>[]]); 
            }
        }
        else
        {
            return response()->json(['message'=>'Parameter <name> should be exist','status_code'=>400]);

        }

       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categorie $categorie,string $id)
    {
        $cate = Categorie::query()->find($id);
            
        if($cate)
        {
            $status = $cate->delete();
            if($status)
                return response()->json(['message'=>'Deleted Successfuly','status_code'=>200,'data'=>[]]);
            else
                return response()->json(['message'=>'Failed','status_code'=>400,'data'=>[]]);
        }else
        {
            return response()->json(['message'=>"Categorie with id $id doesn't found",'status_code'=>400,'data'=>[]]);
        }
        
    }
}
