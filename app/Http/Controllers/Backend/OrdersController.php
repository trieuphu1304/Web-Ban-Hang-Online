<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders;

class OrdersController extends Controller
{
    public function __construct()
    {
        
    }

    public function index() {
        $template = 'backend.orders.index';
        $orders = Orders::All();

        return view('backend.layout', compact(
            'template',
            'orders'
        ));
    }

    public function edit($id) {
        $orders = Orders::find($id);
        $template = 'backend.orders.edit';
        return view('backend.layout', compact(
            'template',
            'orders'
        ));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'order_date' => 'required',
            'status' => 'required|in:pending,processing,completed,canceled',
        ]);
        $orders = Orders::find($id);
        $orders -> name = $request ->input('name');
        $orders -> email = $request->input('email');
        $orders -> email = $request->input('order_date');
        $orders -> status = $request->input('status');
        
        $orders->save();
        return redirect()->route('orders.index')->with('success', 'Danh mục đã được cập nhật thành công!');
    }

    public function delete($id) {
        $orders = Orders::find($id);
        $orders ->delete();
        return redirect()->route('orders.index')->with('success', 'Danh mục đã được xóa!');
    }

    public function show($id)
    {
        // load order + items + product (requires relations in model)
        $order = Orders::with(['items.product'])->findOrFail($id);
        $template = 'backend.orders.show';
        return view('backend.layout', compact('template', 'order'));
    }
}