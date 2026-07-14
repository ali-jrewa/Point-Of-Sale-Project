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

    // ==========================================================
    // Supplier / Customer directory PDFs (already built)
    // ==========================================================

    public function supplier()
    {
        $data = [
            'title' => "Suppliers PDF",
            'date'  => now()->format('Y-m-d H:i'),
        ];

        $supplierChunks = Supplier::all()->chunk(20);

        $pdf = Pdf::loadView('pdf.all_suppliers', compact('data', 'supplierChunks'));

        return $pdf->download('all_supplier_report.pdf');
    }

    public function customer()
    {
        $data = [
            'title' => "Customers PDF",
            'date'  => now()->format('Y-m-d H:i'),
        ];

        $customerChunks = Customer::all()->chunk(20);

        $pdf = Pdf::loadView('pdf.all_customers', compact('data', 'customerChunks'));

        return $pdf->download('all_customers_report.pdf');
    }

    public function supplierRow(Supplier $supplier)
    {
        $supplier->load([
            'purchases' => function ($query) {
                $query->orderBy('purchased_at', 'desc');
            },
            'purchases.items.product',
        ]);

        $data = [
            'title' => "Supplier Report - {$supplier->first_name} {$supplier->last_name}",
            'date'  => now()->format('Y-m-d H:i'),
        ];

        $pdf = Pdf::loadView('pdf.supplier', compact('data', 'supplier'));

        return $pdf->download('supplier_' . $supplier->id . '_report.pdf');
    }

    public function customerRow(Customer $customer)
    {
        $customer->load([
            'sales' => function ($query) {
                $query->orderBy('sold_at', 'desc');
            },
            'sales.items.product',
            'sales.payments',
            'sales.refunds.items.product',
        ]);

        $data = [
            'title' => "Customer Report - {$customer->first_name} {$customer->last_name}",
            'date'  => now()->format('Y-m-d H:i'),
        ];

        $pdf = Pdf::loadView('pdf.customer', compact('data', 'customer'));

        return $pdf->download('customer_' . $customer->id . '_report.pdf');
    }

    // ==========================================================
    // Sales Report
    // ==========================================================

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

        $pdf = Pdf::loadView('pdf.sales', compact('data', 'sales'));

        return $pdf->download('sales_report_' . $from . '_to_' . $to . '.pdf');
    }

    // ==========================================================
    // Purchase Report
    // ==========================================================

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

        $pdf = Pdf::loadView('pdf.purchases', compact('data', 'purchases'));

        return $pdf->download('purchase_report_' . $from . '_to_' . $to . '.pdf');
    }

    // ==========================================================
    // Profit & Loss Report
    // ==========================================================

    public function profitLoss(Request $request)
    {
        [$from, $to] = $this->resolveDateRange($request);

        $totalRevenue = Sale::whereDate('sold_at', '>=', $from)
            ->whereDate('sold_at', '<=', $to)
            ->sum('total');

        $totalCogs = Purchase::whereDate('purchased_at', '>=', $from)
            ->whereDate('purchased_at', '<=', $to)
            ->sum('total');

        $totalExpenses = Expense::whereDate('expense_date', '>=', $from)
            ->whereDate('expense_date', '<=', $to)
            ->sum('amount');

        $totalRefunds = Refund::whereDate('refunded_at', '>=', $from)
            ->whereDate('refunded_at', '<=', $to)
            ->sum('amount');

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

        $pdf = Pdf::loadView('pdf.profit-loss', compact(
            'data',
            'totalRevenue',
            'totalRefunds',
            'netRevenue',
            'totalCogs',
            'grossProfit',
            'totalExpenses',
            'netProfit',
            'expensesByCategory'
        ));

        return $pdf->download('profit_loss_report_' . $from . '_to_' . $to . '.pdf');
    }

    // ==========================================================
    // Expense Report
    // ==========================================================

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

        $pdf = Pdf::loadView('pdf.expenses', compact('data', 'expenses'));

        return $pdf->download('expense_report_' . $from . '_to_' . $to . '.pdf');
    }

    // ==========================================================
    // Payment Report
    // ==========================================================

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

        $pdf = Pdf::loadView('pdf.payments', compact('data', 'payments', 'byMethod'));

        return $pdf->download('payment_report_' . $from . '_to_' . $to . '.pdf');
    }

    // ==========================================================
    // Refund Report
    // ==========================================================

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

        $pdf = Pdf::loadView('pdf.refunds', compact('data', 'refunds'));

        return $pdf->download('refund_report_' . $from . '_to_' . $to . '.pdf');
    }

    // ==========================================================
    // Stock Report
    // ==========================================================

    public function stock()
    {
        $products = Product::with('category')->orderBy('name')->get();

        $totalStockValueCost = $products->sum(fn ($p) => $p->stock_quantity * $p->cost_price);
        $totalStockValueRetail = $products->sum(fn ($p) => $p->stock_quantity * $p->retail_price);

        $data = [
            'title' => 'Stock Report',
            'date'  => now()->format('Y-m-d H:i'),
        ];

        $pdf = Pdf::loadView('pdf.stock', compact(
            'data',
            'products',
            'totalStockValueCost',
            'totalStockValueRetail'
        ));

        return $pdf->download('stock_report.pdf');
    }

    // ==========================================================
    // Low Stock Alert
    // ==========================================================

    public function lowStock()
    {
        $products = Product::with('category')
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->orderBy('stock_quantity')
            ->get();

        $data = [
            'title' => 'Low Stock Alert Report',
            'date'  => now()->format('Y-m-d H:i'),
        ];

        $pdf = Pdf::loadView('pdf.low-stock', compact('data', 'products'));

        return $pdf->download('low_stock_report.pdf');
    }

    // ==========================================================
    // Top Customers
    // ==========================================================

    public function topCustomers(Request $request)
    {
        [$from, $to] = $this->resolveDateRange($request);

        $customers = Customer::withSum(['sales' => function ($query) use ($from, $to) {
            $query->whereDate('sold_at', '>=', $from)
                  ->whereDate('sold_at', '<=', $to);
        }], 'total')
            ->withCount(['sales' => function ($query) use ($from, $to) {
                $query->whereDate('sold_at', '>=', $from)
                      ->whereDate('sold_at', '<=', $to);
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

        $pdf = Pdf::loadView('pdf.top-customers', compact('data', 'customers'));

        return $pdf->download('top_customers_report_' . $from . '_to_' . $to . '.pdf');
    }

    // ==========================================================
    // Due / Outstanding
    // ==========================================================

    public function due()
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

        $pdf = Pdf::loadView('pdf.due', compact('data', 'sales', 'totalDue'));

        return $pdf->download('due_report.pdf');
    }
}
