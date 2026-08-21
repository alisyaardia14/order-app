<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::query()
            ->with('items')
            ->latest()
            ->get();

        $statusCounts = $orders->countBy('status');

        return view('admin.orders.index', [
            'orders' => $orders,
            'statusCounts' => $statusCounts,
            'paymentPage' => false,
        ]);
    }

    public function payments()
    {
        $orders = Order::query()
            ->with('items')
            ->where('status', 'ready')
            ->latest()
            ->get();

        $statusCounts = $orders->countBy('status');

        return view('admin.orders.index', [
            'orders' => $orders,
            'statusCounts' => $statusCounts,
            'paymentPage' => true,
        ]);
    }

    public function revenueReport(Request $request, string $period = 'day')
    {
        $period = in_array($period, ['day', 'month'], true) ? $period : 'day';
        $reportData = $this->prepareRevenueReport($request, $period);

        return view('admin.revenue.index', $reportData);
    }

    public function downloadRevenuePdf(Request $request, string $period = 'day')
    {
        $period = in_array($period, ['day', 'month'], true) ? $period : 'day';
        $reportData = $this->prepareRevenueReport($request, $period);

        return view('admin.revenue.print', $reportData);
    }

    private function prepareRevenueReport(Request $request, string $period): array
    {
        $query = Order::query()->where('status', 'completed');
        $filterSummary = 'Semua periode';
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $startMonth = $request->query('start_month');
        $endMonth = $request->query('end_month');

        if ($period === 'day' && $startDate && $endDate) {
            try {
                $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
                $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
                if ($start->greaterThan($end)) {
                    [$start, $end] = [$end, $start];
                }

                $query->whereBetween('created_at', [$start, $end]);
                $filterSummary = $start->translatedFormat('d F Y') . ' — ' . $end->translatedFormat('d F Y');
            } catch (\Exception $e) {
                // invalid input, ignore filter
            }
        }

        if ($period === 'month' && $startMonth && $endMonth) {
            try {
                $start = Carbon::createFromFormat('Y-m', $startMonth)->startOfMonth();
                $end = Carbon::createFromFormat('Y-m', $endMonth)->endOfMonth();
                if ($start->greaterThan($end)) {
                    [$start, $end] = [$end, $start];
                }

                $query->whereBetween('created_at', [$start, $end]);
                $filterSummary = $start->translatedFormat('F Y') . ' — ' . $end->translatedFormat('F Y');
            } catch (\Exception $e) {
                // invalid input, ignore filter
            }
        }

        $completedOrders = $query->latest('created_at')->get();

        $report = $completedOrders
            ->groupBy(function (Order $order) use ($period): string {
                return $period === 'month'
                    ? $order->created_at->format('Y-m')
                    : $order->created_at->format('Y-m-d');
            })
            ->map(function ($orders, $key) use ($period): array {
                $periodLabel = $period === 'month'
                    ? Carbon::createFromFormat('Y-m', $key)->translatedFormat('F Y')
                    : Carbon::createFromFormat('Y-m-d', $key)->translatedFormat('d F Y');

                return [
                    'period_key' => $key,
                    'label' => $periodLabel,
                    'total_revenue' => $orders->sum('total_amount'),
                    'order_count' => $orders->count(),
                ];
            })
            ->sortByDesc('period_key')
            ->values();

        return [
            'report' => $report,
            'period' => $period,
            'totalRevenue' => $completedOrders->sum('total_amount'),
            'totalOrders' => $completedOrders->count(),
            'filterSummary' => $filterSummary,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'start_month' => $startMonth,
            'end_month' => $endMonth,
        ];
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => [
                'required',
                Rule::in(['pending', 'processing', 'ready', 'completed']),
            ],
        ]);

        $order->update($data);

        if ($request->input('source') === 'payment') {
            return redirect()->route('admin.orders.index')
                ->with('success', "Pembayaran untuk meja {$order->table_number} sudah dibayar. Pesanan selesai.");
        }

        return back()->with('success', "Status meja {$order->table_number} berhasil diperbarui.");
    }
}
