<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $data['title'] }}</title>
    @include('pdf._style')
</head>
<body>

    <div class="header">
        <h2>{{ $data['title'] }}</h2>
        <p>Generated on: {{ $data['date'] }}</p>
    </div>

    <p class="range">Period: {{ $data['from'] }} to {{ $data['to'] }}</p>

    @php $grandTotal = $expenses->sum('amount'); @endphp

    <div class="section-title">Summary</div>
    <table>
        <tr>
            <th width="25%">Total Expenses</th>
            <td>{{ $expenses->count() }}</td>
            <th width="25%">Grand Total</th>
            <td>${{ number_format($grandTotal, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">Expense Detail</div>
    <table>
        <thead>
            <tr>
                <th>Expense #</th>
                <th>Title</th>
                <th>Category</th>
                <th>Vendor</th>
                <th>Method</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $expense)
                <tr>
                    <td>{{ $expense->expense_number }}</td>
                    <td>{{ $expense->title }}</td>
                    <td>{{ $expense->expenseCategory->name ?? '-' }}</td>
                    <td>{{ $expense->vendor_name ?? '-' }}</td>
                    <td>{{ ucfirst(str_replace('_',' ',$expense->payment_method)) }}</td>
                    <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d') }}</td>
                    <td>${{ number_format($expense->amount, 2) }}</td>
                    <td>{{ ucfirst($expense->status) }}</td>
                </tr>
            @empty
                <tr><td colspan="8">No expenses found for this period.</td></tr>
            @endforelse
            <tr class="totals-row">
                <td colspan="6">Total</td>
                <td>${{ number_format($grandTotal, 2) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
