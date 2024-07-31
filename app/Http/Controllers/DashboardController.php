<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Reminder;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data statistik utama
        $totalSales = Invoice::sum('amount');
        $activeOrders = Order::where('status', 'active')->count();
        $customerSatisfaction = $this->calculateCustomerSatisfaction();

        // Ambil aktivitas terbaru
        $recentActivities = $this->getRecentActivities();

        // Ambil notifikasi penting
        $importantNotifications = $this->getImportantNotifications();

        return view('dashboard', compact('totalSales', 'activeOrders', 'customerSatisfaction', 'recentActivities', 'importantNotifications'));
    }

    private function calculateCustomerSatisfaction()
    {
        // Logika untuk menghitung kepuasan pelanggan (placeholder)
        return 90; // Placeholder value
    }

    private function getRecentActivities()
    {
        // Logika untuk mendapatkan aktivitas terbaru (placeholder)
        return Order::orderBy('created_at', 'desc')->take(5)->get();
    }

    private function getImportantNotifications()
    {
        // Logika untuk mendapatkan notifikasi penting (placeholder)
        return Reminder::orderBy('reminder_date', 'asc')->take(5)->get();
    }
}
