<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SeafoodItem;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.reports.index', $this->buildReportData($request));
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $data = $this->buildReportData($request);
        $fileName = 'laporan-seafood-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($data): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Laporan Seafood']);
            fputcsv($handle, ['Periode', $data['filterLabel']]);
            fputcsv($handle, []);
            fputcsv($handle, ['Ringkasan']);
            fputcsv($handle, ['Total Penjualan', $data['summary']['total_sales']]);
            fputcsv($handle, ['Penjualan Lunas', $data['summary']['paid_sales']]);
            fputcsv($handle, ['Total Pesanan', $data['summary']['total_orders']]);
            fputcsv($handle, ['Total Menu', $data['summary']['total_menu']]);
            fputcsv($handle, ['Total Pelanggan', $data['summary']['total_customers']]);
            fputcsv($handle, []);
            fputcsv($handle, ['Status Pesanan']);
            fputcsv($handle, ['Status', 'Jumlah']);

            foreach ($data['orderStatusSummary'] as $row) {
                fputcsv($handle, [$row->status, $row->total]);
            }

            fputcsv($handle, []);
            fputcsv($handle, ['Menu Terlaris']);
            fputcsv($handle, ['Menu', 'Qty', 'Penjualan']);

            foreach ($data['topItems'] as $item) {
                fputcsv($handle, [$item->item_name, $item->total_quantity, $item->total_sales]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportPdf(Request $request): View
    {
        return view('admin.reports.pdf', $this->buildReportData($request));
    }

    private function buildReportData(Request $request): array
    {
        [$startDate, $endDate, $filterLabel] = $this->resolveDateRange($request);

        $ordersQuery = Order::query();

        if ($startDate && $endDate) {
            $ordersQuery->whereBetween('created_at', [
                $startDate->copy()->startOfDay(),
                $endDate->copy()->endOfDay(),
            ]);
        }

        $orderIds = (clone $ordersQuery)->pluck('id');
        $orders = (clone $ordersQuery)->get();

        $orderStatusSummary = (clone $ordersQuery)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        $topItems = DB::table('order_items')
            ->when($orderIds->isNotEmpty(), fn ($query) => $query->whereIn('order_id', $orderIds))
            ->when($orderIds->isEmpty() && ($startDate || $endDate), fn ($query) => $query->whereRaw('1 = 0'))
            ->select('item_name', DB::raw('sum(quantity) as total_quantity'), DB::raw('sum(line_total) as total_sales'))
            ->groupBy('item_name')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        return [
            'filters' => [
                'month' => $request->string('month')->toString(),
                'start_date' => $startDate?->format('Y-m-d'),
                'end_date' => $endDate?->format('Y-m-d'),
            ],
            'filterLabel' => $filterLabel,
            'summary' => [
                'total_sales' => (float) $orders->sum('total_amount'),
                'paid_sales' => (float) $orders->where('payment_status', 'paid')->sum('total_amount'),
                'total_orders' => $orders->count(),
                'total_menu' => SeafoodItem::count(),
                'total_customers' => User::whereHas('role', fn ($query) => $query->where('name', 'pelanggan'))->count(),
            ],
            'orderStatusSummary' => $orderStatusSummary,
            'topItems' => $topItems,
        ];
    }

    private function resolveDateRange(Request $request): array
    {
        $month = $request->string('month')->toString();

        if ($month !== '') {
            $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();

            return [$startDate, $endDate, $startDate->translatedFormat('F Y')];
        }

        $startDateInput = $request->string('start_date')->toString();
        $endDateInput = $request->string('end_date')->toString();

        if ($startDateInput !== '' || $endDateInput !== '') {
            $startDate = $startDateInput !== '' ? Carbon::parse($startDateInput) : Carbon::create(2000, 1, 1);
            $endDate = $endDateInput !== '' ? Carbon::parse($endDateInput) : now();

            return [
                $startDate,
                $endDate,
                $startDate->format('d/m/Y').' - '.$endDate->format('d/m/Y'),
            ];
        }

        return [null, null, 'Semua Periode'];
    }
}
