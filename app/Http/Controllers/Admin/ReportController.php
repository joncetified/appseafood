<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SeafoodItem;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.reports.index', $this->buildReportData($request));
    }

    public function exportExcel(Request $request): Response
    {
        $data = $this->buildReportData($request);
        $fileName = 'laporan-seafood-'.now()->format('Ymd-His').'.xls';

        return response()
            ->view('admin.reports.excel', $data)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$fileName.'"');
    }

    public function exportPdf(Request $request): View
    {
        return view('admin.reports.pdf', [
            ...$this->buildReportData($request),
            'autoPrint' => false,
        ]);
    }

    public function print(Request $request): View
    {
        return view('admin.reports.pdf', [
            ...$this->buildReportData($request),
            'autoPrint' => true,
        ]);
    }

    private function buildReportData(Request $request): array
    {
        [$startDate, $endDate, $filterLabel] = $this->resolveDateRange($request);

        $ordersQuery = Order::query()->with('items');

        if ($startDate && $endDate) {
            $ordersQuery->whereBetween('created_at', [
                $startDate->copy()->startOfDay(),
                $endDate->copy()->endOfDay(),
            ]);
        }

        $orderIds = (clone $ordersQuery)->pluck('id');
        $orders = (clone $ordersQuery)->orderByDesc('created_at')->get();

        $orderStatusSummary = (clone $ordersQuery)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderBy('status')
            ->get()
            ->map(function ($row) {
                $row->label = $this->formatLabel($row->status);

                return $row;
            });

        $topItems = DB::table('order_items')
            ->when($orderIds->isNotEmpty(), fn ($query) => $query->whereIn('order_id', $orderIds))
            ->when($orderIds->isEmpty() && ($startDate || $endDate), fn ($query) => $query->whereRaw('1 = 0'))
            ->select('item_name', DB::raw('sum(quantity) as total_quantity'), DB::raw('sum(line_total) as total_sales'))
            ->groupBy('item_name')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        $detailedOrders = $orders->map(function (Order $order) {
            return [
                'order_number' => $order->order_number,
                'date' => $order->created_at?->format('d/m/Y H:i'),
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone ?: '-',
                'status' => $this->formatLabel($order->status),
                'payment_status' => $this->formatLabel($order->payment_status),
                'total_quantity' => $order->items->sum('quantity'),
                'total_amount' => (float) $order->total_amount,
                'items_summary' => $order->items
                    ->map(fn ($item) => $item->item_name.' x'.$item->quantity)
                    ->implode(', '),
            ];
        });

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
            'detailedOrders' => $detailedOrders,
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

    private function formatLabel(string $value): string
    {
        return str($value)->replace('_', ' ')->title()->toString();
    }
}
