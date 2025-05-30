<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Monthly revenue
        $currentMonthRevenue = Order::where('status', '=', 'completed')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('total_price');
        $lastMonthRevenue = Order::where('status', '!=', 'cancelled')
            ->whereYear('created_at', now()->subMonth()->year)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->sum('total_price');

        $percentageChange = $lastMonthRevenue > 0
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : 100;

        // Monthly user
        $totalUsers = User::where('role', 'user')->count();
        $currentMonthUsers = User::where('role', 'user')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $lastMonthUsers = User::where('role', 'user')
            ->whereYear('created_at', now()->subMonth()->year)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->count();
        $userGrowth = $lastMonthUsers > 0
            ? (($currentMonthUsers - $lastMonthUsers) / $lastMonthUsers) * 100
            : 100;

        // Pending status order
        $pendingOrders = Order::where('status', 'pending')->count();

        // Completed status order
        $completedOrders = Order::where('status', 'completed')->count();

        // Chart
        $completedOrdersPerMonth = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');
        $months = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des'
        ];
        $orderChartLabels = [];
        $orderChartData = [];
        foreach ($months as $monthNum => $monthName) {
            $orderChartLabels[] = $monthName;
            $orderChartData[] = $completedOrdersPerMonth[$monthNum] ?? 0;
        }

        // Ranking product
        $topProductsQuery = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->with('product');  // eager load product relation jika ada

        $topProducts = $topProductsQuery->paginate(6);

        return view('admin.index', [
            'currentMonthRevenue' => $currentMonthRevenue,
            'totalUsers' => $totalUsers,
            'pendingOrders' => $pendingOrders,
            'completedOrders' => $completedOrders,
            'orderChartLabels' => $orderChartLabels,
            'orderChartData' => $orderChartData,
            'topProducts' => $topProducts,
        ]);
    }
}
