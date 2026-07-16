<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Refund;
use App\Models\Sale;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Shared helper to resolve a date range from the request,
     * defaulting to the current month.
     */
    private function resolveDateRange(Request $request): array
    {
        $from = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->input('to', now()->endOfMonth()->format('Y-m-d'));

        return [$from, $to];
    }

    private function renderReport(Request $request, string $view, array $viewData, string $filename)
    {
        if ($request->query('format') === 'pdf') {
            return Pdf::loadView($view, $viewData)->download($filename);
        }

        return view($view, $viewData + [
            'preview'     => true,
            'downloadUrl' => $request->fullUrlWithQuery(['format' => 'pdf']),
            'backUrl'     => $this->dashboardUrl(),
        ]);
    }

    private function dashboardUrl(): string
    {
        $role = Auth::user()->role->name ?? 'admin';

        return match ($role) {
            'manager' => url('/manager/dashboard#reports-menu'),
            'cashier' => url('/cashier/dashboard#reports-menu'),
            default   => url('/admin/dashboard#reports-menu'),
        };
    }

    // ==========================================================
    // Supplier / Customer directory PDFs (already built)
    // ==========================================================

    public function supplier(Request $request)
    {
        $data = [
            'title' => "Suppliers PDF",
            'date'  => now()->format('Y-m-d H:i'),
        ];

        $supplierChunks = Supplier::all()->chunk(20);

        return $this->renderReport($request, 'pdf.all_suppliers', compact('data', 'supplierChunks'), 'all_supplier_report.pdf');
    }

    public function customer(Request $request)
    {
        $data = [
            'title' => "Customers PDF",
            'date'  => now()->format('Y-m-d H:i'),
        ];

        $customerChunks = Customer::all()->chunk(20);

        return $this->renderReport($request, 'pdf.all_customers', compact('data', 'customerChunks'), 'all_customers_report.pdf');
    }

    public function supplierRow(Request $request, Supplier $supplier)
    {
        $supplier->load([
            'purchases' => fn ($q) => $q->orderBy('purchased_at', 'desc'),
            'purchases.items.product',
        ]);

        $data = [
            'title' => "Supplier Report - {$supplier->first_name} {$supplier->last_name}",
            'date'  => now()->format('Y-m-d H:i'),
        ];

        return $this->renderReport($request, 'pdf.supplier', compact('data', 'supplier'), 'supplier_' . $supplier->id . '_report.pdf');
    }

    public function customerRow(Request $request, Customer $customer)
    {
        $customer->load([
            'sales' => fn ($q) => $q->orderBy('sold_at', 'desc'),
            'sales.items.product',
            'sales.payments',
            'sales.refunds.items.product',
        ]);

        $data = [
            'title' => "Customer Report - {$customer->first_name} {$customer->last_name}",
            'date'  => now()->format('Y-m-d H:i'),
        ];

        return $this->renderReport($request, 'pdf.customer', compact('data', 'customer'), 'customer_' . $customer->id . '_report.pdf');
    }

    public function sales(Request $request)
    {
        [$from, $to] = $this->resolveDateRange($request);

        $sales = Sale::with('customer')
            ->whereDate('sold_at', '>=', $from)
            ->whereDate('sold_at', '<=', $to)
            ->orderBy('sold_at')
            ->get();

        $data = [
            'title' => 'Sales Report',
            'date'  => now()->format('Y-m-d H:i'),
            'from'  => $from,
            'to'    => $to,
        ];

        return $this->renderReport($request, 'pdf.sales', compact('data', 'sales'), 'sales_report_' . $from . '_to_' . $to . '.pdf');
    }

    public function purchases(Request $request)
    {
        [$from, $to] = $this->resolveDateRange($request);

        $purchases = Purchase::with('supplier')
            ->whereDate('purchased_at', '>=', $from)
            ->whereDate('purchased_at', '<=', $to)
            ->orderBy('purchased_at')
            ->get();

        $data = [
            'title' => 'Purchase Report',
            'date'  => now()->format('Y-m-d H:i'),
            'from'  => $from,
            'to'    => $to,
        ];

        return $this->renderReport($request, 'pdf.purchases', compact('data', 'purchases'), 'purchase_report_' . $from . '_to_' . $to . '.pdf');
    }

    public function profitLoss(Request $request)
    {
        [$from, $to] = $this->resolveDateRange($request);

        $totalRevenue = Sale::whereDate('sold_at', '>=', $from)->whereDate('sold_at', '<=', $to)->sum('total');
        $totalCogs = Purchase::whereDate('purchased_at', '>=', $from)->whereDate('purchased_at', '<=', $to)->sum('total');
        $totalExpenses = Expense::whereDate('expense_date', '>=', $from)->whereDate('expense_date', '<=', $to)->sum('amount');
        $totalRefunds = Refund::whereDate('refunded_at', '>=', $from)->whereDate('refunded_at', '<=', $to)->sum('amount');

        $netRevenue = $totalRevenue - $totalRefunds;
        $grossProfit = $netRevenue - $totalCogs;
        $netProfit = $grossProfit - $totalExpenses;

        $expensesByCategory = Expense::with('expenseCategory')
            ->whereDate('expense_date', '>=', $from)
            ->whereDate('expense_date', '<=', $to)
            ->get()
            ->groupBy(fn ($e) => $e->expenseCategory->name ?? 'Uncategorized')
            ->map(fn ($group) => $group->sum('amount'));

        $data = [
            'title' => 'Profit & Loss Report',
            'date'  => now()->format('Y-m-d H:i'),
            'from'  => $from,
            'to'    => $to,
        ];

        return $this->renderReport(
            $request,
            'pdf.profit-loss',
            compact('data', 'totalRevenue', 'totalRefunds', 'netRevenue', 'totalCogs', 'grossProfit', 'totalExpenses', 'netProfit', 'expensesByCategory'),
            'profit_loss_report_' . $from . '_to_' . $to . '.pdf'
        );
    }

    public function expenses(Request $request)
    {
        [$from, $to] = $this->resolveDateRange($request);

        $expenses = Expense::with('expenseCategory')
            ->whereDate('expense_date', '>=', $from)
            ->whereDate('expense_date', '<=', $to)
            ->orderBy('expense_date')
            ->get();

        $data = [
            'title' => 'Expense Report',
            'date'  => now()->format('Y-m-d H:i'),
            'from'  => $from,
            'to'    => $to,
        ];

        return $this->renderReport($request, 'pdf.expenses', compact('data', 'expenses'), 'expense_report_' . $from . '_to_' . $to . '.pdf');
    }

    public function payments(Request $request)
    {
        [$from, $to] = $this->resolveDateRange($request);

        $payments = Payment::with(['sale.customer', 'user'])
            ->whereDate('paid_at', '>=', $from)
            ->whereDate('paid_at', '<=', $to)
            ->orderBy('paid_at')
            ->get();

        $byMethod = $payments->groupBy('method')->map(fn ($group) => $group->sum('amount'));

        $data = [
            'title' => 'Payment Report',
            'date'  => now()->format('Y-m-d H:i'),
            'from'  => $from,
            'to'    => $to,
        ];

        return $this->renderReport($request, 'pdf.payments', compact('data', 'payments', 'byMethod'), 'payment_report_' . $from . '_to_' . $to . '.pdf');
    }

    public function refunds(Request $request)
    {
        [$from, $to] = $this->resolveDateRange($request);

        $refunds = Refund::with(['sale.customer', 'items.product'])
            ->whereDate('refunded_at', '>=', $from)
            ->whereDate('refunded_at', '<=', $to)
            ->orderBy('refunded_at')
            ->get();

        $data = [
            'title' => 'Refund Report',
            'date'  => now()->format('Y-m-d H:i'),
            'from'  => $from,
            'to'    => $to,
        ];

        return $this->renderReport($request, 'pdf.refunds', compact('data', 'refunds'), 'refund_report_' . $from . '_to_' . $to . '.pdf');
    }

    public function stock(Request $request)
    {
        $products = Product::with('category')->orderBy('name')->get();

        $totalStockValueCost = $products->sum(fn ($p) => $p->stock_quantity * $p->cost_price);
        $totalStockValueRetail = $products->sum(fn ($p) => $p->stock_quantity * $p->retail_price);

        $data = [
            'title' => 'Stock Report',
            'date'  => now()->format('Y-m-d H:i'),
        ];

        return $this->renderReport(
            $request,
            'pdf.stock',
            compact('data', 'products', 'totalStockValueCost', 'totalStockValueRetail'),
            'stock_report.pdf'
        );
    }

    public function lowStock(Request $request)
    {
        $products = Product::with('category')
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->orderBy('stock_quantity')
            ->get();

        $data = [
            'title' => 'Low Stock Alert Report',
            'date'  => now()->format('Y-m-d H:i'),
        ];

        return $this->renderReport($request, 'pdf.low-stock', compact('data', 'products'), 'low_stock_report.pdf');
    }

    public function topCustomers(Request $request)
    {
        [$from, $to] = $this->resolveDateRange($request);

        $customers = Customer::withSum(['sales' => function ($query) use ($from, $to) {
            $query->whereDate('sold_at', '>=', $from)->whereDate('sold_at', '<=', $to);
        }], 'total')
            ->withCount(['sales' => function ($query) use ($from, $to) {
                $query->whereDate('sold_at', '>=', $from)->whereDate('sold_at', '<=', $to);
            }])
            ->having('sales_sum_total', '>', 0)
            ->orderByDesc('sales_sum_total')
            ->limit(20)
            ->get();

        $data = [
            'title' => 'Top Customers Report',
            'date'  => now()->format('Y-m-d H:i'),
            'from'  => $from,
            'to'    => $to,
        ];

        return $this->renderReport($request, 'pdf.top-customers', compact('data', 'customers'), 'top_customers_report_' . $from . '_to_' . $to . '.pdf');
    }

    public function due(Request $request)
    {
        $sales = Sale::with('customer')
            ->where('due_amount', '>', 0)
            ->orderByDesc('due_amount')
            ->get();

        $totalDue = $sales->sum('due_amount');

        $data = [
            'title' => 'Due / Outstanding Report',
            'date'  => now()->format('Y-m-d H:i'),
        ];

        return $this->renderReport($request, 'pdf.due', compact('data', 'sales', 'totalDue'), 'due_report.pdf');
    }
}
