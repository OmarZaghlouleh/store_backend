<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{



    // public function __construct() {
    //This middleware will be implemented on all functions below unless we use expect 
//     $this->middleware('auth')->except('index');
// }   
    public function index()
    {
        $categories = Category::get();

        return response()->json(
            ['success' => true, 'status_code' => 200, 'data' => $categories]
        );
    }

    public function show(Request $request)
    {
        $category = Category::find($request->id);
        if (!is_null($category)) {
            return response()->json(['success' => true, 'status_code' => 200, 'data' => $category]);
        } else {
            return response()->json(['success' => false, 'status_code' => 404, 'message' => "Category with id $request->id doesn't found"]);

        }

    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->only('name'), [
            'name' => ['required', 'string', 'between:2,255', 'unique:categories,name']
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'status_code' => 400, 'message' => $validator->errors()->first()]);
        } else {

            $category = Category::create($request->only('name'), [
                'name' => $request->name
            ]);

            return response()->json(['success' => true, 'status_code' => 200, 'data' => $category]);
        }

    }
    public function update(Request $request)
    {

        $validator = Validator::make($request->only('name'), [
            'name' => ['required', 'string', 'between:2,255', 'unique:categories,name']
        ]);

        if ($validator->fails()) {

            return response()->json(['success' => false, 'status_code' => 400, 'message' => $validator->errors()->first()]);
        } else {

            $category = Category::find($request->id);


            if (!is_null($category)) {
                $category->update(['name' => $request->name]);
                return response()->json(['success' => true, 'status_code' => 200, 'message' => 'Updated successfully', 'data' => $category]);

            } else {
                return response()->json(['success' => false, 'status_code' => 404, 'message' => "Category with id $request->id doesn't found"]);

            }
        }

    }


    public function search(Request $request)
    {

        $categories = Category::where('name', 'like', '%' . $request->name . '%')->get();

        return response()->json([
            'success'     => true,
            'status_code' => 200,
            'data'        => $categories
        ]);

    }

    public function delete($id)
    {


        $category = Category::find($id);

        if (!is_null($category)) {

            if ($category->destroy($id)) {
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
                'message'     => "Category with id $id doesn't found."
            ]);
        }






    }
}