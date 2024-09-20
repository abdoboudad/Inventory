<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->with('supplier', 'items.inventory')->simplePaginate(3);
        return response()->json($orders);
    }

    public function search(Request $request){
        if($request->status){
            $orders = Order::where('status',$request->status)->with('supplier', 'items.inventory')->get();
            return response()->json($orders);
        }
        elseif($request->supplier){
            // $orders = Order::all();
            $orders = Order::whereHas('supplier', function($query) use ($request){$query->where('name','LIKE',"%$request->supplier%");})->with('supplier', 'items.inventory')->get();
            return response()->json($orders);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string|unique:orders',
            'supplier_id' => 'required|exists:suppliers,id',
            'status' => 'required|in:pending,completed,shipped,cancelled',
            'total_amount' => 'required|numeric',
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date',
            'items' => 'required|array',
            'items.*.inventory_id' => 'required|exists:inventories,id',
            'items.*.quantity' => 'required|integer',
            'items.*.price' => 'required|numeric',
        ]);

        $order = Order::create($validated);
        foreach ($validated['items'] as $item) {
            $order->items()->create($item);
        }

        return response()->json($order, 201);
    }

    public function show($id)
    {
        $order = Order::with('supplier', 'items.inventory')->findOrFail($id);
        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'order_number' => 'sometimes|string|unique:orders,order_number,' . $id,
            'supplier_id' => 'sometimes|exists:suppliers,id',
            'status' => 'sometimes|in:pending,completed,shipped,cancelled',
            'total_amount' => 'sometimes|numeric',
            'order_date' => 'sometimes|date',
            'delivery_date' => 'nullable|date',
            'items' => 'sometimes|array',
            'items.*.inventory_id' => 'sometimes|exists:inventories,id',
            'items.*.quantity' => 'sometimes|integer',
            'items.*.price' => 'sometimes|numeric',
        ]);

        $order = Order::findOrFail($id);
        $order->update($validated);

        if (isset($validated['items'])) {
            $order->items()->delete();
            foreach ($validated['items'] as $item) {
                $order->items()->create($item);
            }
        }

        return response()->json($order);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->items()->delete();
        $order->delete();
        return response()->json(null, 204);
    }
}
