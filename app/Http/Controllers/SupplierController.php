<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{

    public function index()
    {
        return Supplier::all();
    }

    public function mainSupplierData(){
        return Supplier::latest()->simplePaginate(3);
    }

    public function search(Request $request)
    {
        // Get the search query from the request
        $query = $request->input('query');

        // Search your database (replace YourModel with your actual model)
        $results = Supplier::where('name', 'LIKE', "%$query%")->get();

        // Return the results as JSON
        return response()->json($results);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'nullable|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $supplier = Supplier::create($validatedData);

        return response()->json($supplier, 201);
    }

    public function show(Supplier $supplier)
    {
        return $supplier;
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'nullable|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $supplier->update($validatedData);

        return response()->json($supplier, 200);
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return response()->json(null, 204);
    }
}
