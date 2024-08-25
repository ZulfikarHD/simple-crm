<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\StockMovement;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Key Metrics
        $totalSales = Invoice::where('status', 'paid')->sum('amount_paid');
        $activeOrders = Order::where('status', 'active')->count();
        $customerSatisfaction = $this->calculateCustomerSatisfaction();
        $lowStockItems = $this->getLowStockItemsCount();

        // Sales Data for Chart
        $salesData = $this->getSalesData();
        $salesCategories = $this->getSalesCategories();

        // Order Status Data for Chart
        $ordersData = $this->getOrdersData();
        $orderStatuses = ['Completed', 'Processing', 'Canceled'];

        // Top Selling Items
        $topSellingItems = $this->getTopSellingItems();

        // Recurring Customers
        $recurringCustomers = $this->getRecurringCustomers();

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
            'topSellingItems',
            'recurringCustomers',
            'recentActivities'
        ));
    }

    private function calculateCustomerSatisfaction()
    {
        return 90; // Placeholder logic
    }

    private function getSalesData()
    {
        $salesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $salesData[] = Invoice::whereDate('created_at', $date)->sum('amount_paid');
        }
        return $salesData;
    }

    private function getSalesCategories()
    {
        $categories = [];
        for ($i = 6; $i >= 0; $i--) {
            $categories[] = Carbon::now()->subDays($i)->format('d M');
        }
        return $categories;
    }

    private function getOrdersData()
    {
        return [
            Order::where('status', 'completed')->count(),
            Order::where('status', 'processing')->count(),
            Order::where('status', 'canceled')->count(),
        ];
    }

    private function getLowStockItemsCount()
    {
        return Inventory::with('stockMovements')->get()->filter(function ($inventory) {
            return $inventory->stockMovements->sum('quantity') <= 10;
        })->count();
    }

    private function getTopSellingItems()
    {
        return Inventory::withSum('stockMovements as total_sold', 'quantity')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();
    }

    private function getRecurringCustomers()
    {
        return Customer::has('orders', '>', 1)->get();
    }

    private function getRecentActivities()
    {
        return Order::latest()->take(5)->get();
    }
}
