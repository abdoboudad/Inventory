<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Supplier;
use Illuminate\Http\Request;

class AnalyticController extends Controller
{
    public function index(){
        $orders = Order::all()->count();
        $orders_list = Order::latest()->with('supplier')->take(5)->get();
        $inventories = Inventory::all()->count();
        $suppliers = Supplier::all()->count();
        $orders_items = OrderItem::all()->count();
        return response()->json([
            'orders'=>$orders,
            'inventories'=>$inventories,
            'suppliers'=>$suppliers,
            'orders_items'=>$orders_items,
            'orders_list'=>$orders_list
        ],201);
    }
}
