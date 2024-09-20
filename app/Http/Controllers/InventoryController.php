<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{  public function index()
    {
        $inventories = Inventory::latest()->simplePaginate(3);
        return response()->json($inventories);
    }

    public function search(Request $request)
    {
        $results = null;
        if($request->input('query')){
            // Get the search query from the request
            $query = $request->input('query');

            // Search your database (replace YourModel with your actual model)
            $results = Inventory::where('item_name', 'LIKE', "%$query%")->get();
        }
        elseif($request->input('category')){
            // Get the search query from the request
            $query = $request->input('category');

            // Search your database (replace YourModel with your actual model)
            $results = Inventory::where('category',$query)->get();
        }


        // Return the results as JSON
        return response()->json($results);
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:inventories',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'supplier' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'status' => 'required|in:in stock,out of stock,discontinued',
        ]);

        Inventory::create($request->all());

        return  response()->json([
            'msg'=>'item created successfully!'
        ]);;
    }

    public function show(Inventory $inventory)
    {
        return  response()->json($inventory);;
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:inventories,sku,' . $inventory->id,
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'supplier' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'status' => 'required|in:in stock,out of stock,discontinued',
        ]);

        $inventory->update($request->all());

        return response()->json([
            'msg'=>'item Updated successfuly!'
        ]);
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return  response()->json([
            'msg'=>'item deleted successfuly!'
        ]);
    }
}
