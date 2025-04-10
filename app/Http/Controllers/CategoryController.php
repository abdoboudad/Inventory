<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        return response()->json($categories);
    }
    public function mainCategories(){
        $categories = Category::latest()->simplePaginate(3);
        return response()->json($categories);
    }
    public function search(Request $request)
    {
        $query = $request->input('query');

        $results = Category::where('title', 'LIKE', "%$query%")->get();

        return response()->json($results);
    }
    public function store(Request $request){
        $validator = $request->validate([
            'title'=>'required|max:255'
        ]);
        $category = Category::create([
            'title'=>$validator['title'],
        ]);
        return $category;
    }
    public function show($id){
        $category = Category::findOrfail($id);
        return $category;
    }
    public function update(Request $request,$id){
        $categoryFind = Category::findOrfail($id);

        $validator = $request->validate([
            'title'=>'required|max:255'
        ]);
        $category = $categoryFind->update([
            'title'=>$validator['title'],
        ]);
        return $category;
    }
    public function destroy($id){
        $categoryFind = Category::findOrfail($id)->delete();
        return response()->json([
            'msg'=>'item deleted successfuly!'
        ]);
    }
}
