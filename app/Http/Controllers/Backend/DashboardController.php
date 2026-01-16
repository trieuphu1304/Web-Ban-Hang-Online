<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Products;
use App\Models\Orders;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        
    }

    public function layout() {
        // Thống kê cơ bản
        $totalStock = Products::where('quantity', '>', 0)->count();
        $totalCustomers = User::count();
        // Tổng đơn đã giao (completed)
        $totalDelivered = Orders::where('status', 'completed')->count();
        // Tổng doanh thu: tổng `total_price` của các orderitem thuộc đơn đã hoàn thành
        $totalRevenue = DB::table('orders')
            ->join('orderitem', 'orders.id', '=', 'orderitem.order_id')
            ->where('orders.status', 'completed')
            ->sum('orderitem.total_price');

        // Top sản phẩm bán chạy (theo số lượng)
        $top = DB::table('orderitem')
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        $productIds = $top->pluck('product_id')->toArray();
        $products = Products::whereIn('id', $productIds)->get()->keyBy('id');

        $topProducts = $top->map(function ($row) use ($products) {
            return [
                'name' => $products[$row->product_id]->name ?? 'N/A',
                'total_sold' => (int) $row->total_sold,
            ];
        });

        // Doanh thu theo ngày (30 ngày gần nhất)
        $sales = DB::table('orders')
            ->join('orderitem', 'orders.id', '=', 'orderitem.order_id')
            ->where('orders.order_date', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(orders.order_date) as date'), DB::raw('SUM(orderitem.total_price) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $template = 'backend.home.index';

        return view('backend.layout', compact(
            'template',
            'totalStock',
            'totalCustomers',
            'totalDelivered',
            'totalRevenue',
            'topProducts',
            'sales'
        ));
    }
}