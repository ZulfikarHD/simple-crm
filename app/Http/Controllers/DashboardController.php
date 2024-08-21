<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Inventory;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Key Metrics
        $totalSales = Invoice::where('status', 'paid')->sum('amount_paid');
        $activeOrders = Order::where('status', 'active')->count();
        $customerSatisfaction = $this->calculateCustomerSatisfaction();
        $lowStockItems = Inventory::where('quantity', '<=', 10)->count();

        // Sales Data for Chart
        $salesData = $this->getSalesData();
        $salesCategories = $this->getSalesCategories();

        // Order Status Data for Chart
        $ordersData = $this->getOrdersData();
        $orderStatuses = ['Completed', 'Processing', 'Canceled'];

        // Recent Activities
        $recentActivities = $this->getRecentActivities();

        return view('dashboard', compact(
            'totalSales',
            'activeOrders',
            'customerSatisfaction',
            'lowStockItems',
            'salesData',
            'salesCategories',
            'ordersData',
            'orderStatuses',
            'recentActivities'
        ));
    }

    private function calculateCustomerSatisfaction()
    {
        // Placeholder for actual logic
        return 90; // e.g., percentage based on feedback
    }

    private function getSalesData()
    {
        // Retrieve sales data for the past 7 days
        $salesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $salesData[] = Invoice::whereDate('created_at', $date)->sum('amount_paid');
        }
        return $salesData;
    }

    private function getSalesCategories()
    {
        // Retrieve the date labels for the past 7 days
        $categories = [];
        for ($i = 6; $i >= 0; $i--) {
            $categories[] = Carbon::now()->subDays($i)->format('d M');
        }
        return $categories;
    }

    private function getOrdersData()
    {
        // Count orders by status
        $completed = Order::where('status', 'completed')->count();
        $processing = Order::where('status', 'processing')->count();
        $canceled = Order::where('status', 'canceled')->count();

        return [$completed, $processing, $canceled];
    }

    private function getRecentActivities()
    {
        // Retrieve the 5 most recent orders, payments, or customer interactions
        return Order::latest()->take(5)->get();
    }
}
