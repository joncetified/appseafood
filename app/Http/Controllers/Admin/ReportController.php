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
        [$chartStartDate, $chartEndDate, $chartWindowLabel] = $this->resolveChartWindow($request, $startDate, $endDate);
        $groupBy = $request->string('group_by')->toString() ?: 'monthly';
        $chartType = $request->string('chart_type')->toString() ?: 'bar';

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

        $chartSummary = $this->buildChartSummary($groupBy, $chartStartDate, $chartEndDate);

        return [
            'filters' => [
                'month' => $request->string('month')->toString(),
                'start_date' => $startDate?->format('Y-m-d'),
                'end_date' => $endDate?->format('Y-m-d'),
                'group_by' => $groupBy,
                'chart_type' => $chartType,
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
            'chart' => [
                'type' => $chartType,
                'group_by' => $groupBy,
                'window_label' => $chartWindowLabel,
                'labels' => $chartSummary->pluck('label')->values(),
                'sales' => $chartSummary->pluck('total_sales')->map(fn ($value) => (float) $value)->values(),
                'orders' => $chartSummary->pluck('total_orders')->map(fn ($value) => (int) $value)->values(),
            ],
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

    private function resolveChartWindow(Request $request, ?Carbon $startDate, ?Carbon $endDate): array
    {
        $groupBy = $request->string('group_by')->toString() ?: 'monthly';

        if ($startDate && $endDate) {
            return [$startDate, $endDate, 'Mengikuti filter laporan'];
        }

        return match ($groupBy) {
            'daily' => [now()->subDays(6), now(), '7 hari terakhir'],
            'weekly' => [now()->subWeeks(7)->startOfWeek(), now()->endOfWeek(), '8 minggu terakhir'],
            'yearly' => [now()->subYears(4)->startOfYear(), now()->endOfYear(), '5 tahun terakhir'],
            default => [now()->subMonths(5)->startOfMonth(), now()->endOfMonth(), '6 bulan terakhir'],
        };
    }

    private function buildChartSummary(string $groupBy, Carbon $startDate, Carbon $endDate)
    {
        $labelExpression = match ($groupBy) {
            'daily' => "DATE_FORMAT(created_at, '%d/%m')",
            'weekly' => "CONCAT(YEAR(created_at), '-W', LPAD(WEEK(created_at, 1), 2, '0'))",
            'yearly' => "DATE_FORMAT(created_at, '%Y')",
            default => "DATE_FORMAT(created_at, '%b %Y')",
        };

        $sortExpression = match ($groupBy) {
            'daily' => "DATE(created_at)",
            'weekly' => "STR_TO_DATE(CONCAT(YEAR(created_at), WEEK(created_at, 1), ' Monday'), '%X%V %W')",
            'yearly' => "DATE_FORMAT(created_at, '%Y-01-01')",
            default => "DATE_FORMAT(created_at, '%Y-%m-01')",
        };

        return Order::query()
            ->whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->selectRaw("{$labelExpression} as label")
            ->selectRaw("{$sortExpression} as sort_key")
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw('SUM(total_amount) as total_sales')
            ->groupBy('label', 'sort_key')
            ->orderBy('sort_key')
            ->get();
    }

    private function formatLabel(string $value): string
    {
        return str($value)->replace('_', ' ')->title()->toString();
    }
}
