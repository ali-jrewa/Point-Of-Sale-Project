<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $data['title'] }}</title>
    @include('pdf._style')
</head>
<body>
    @include('pdf._toolbar')

    <div class="header">
        <h2>{{ $data['title'] }}</h2>
        <p>{{ __('report/expenses.generated_on') }}: {{ $data['date'] }}</p>
    </div>

    <p class="range">{{ __('report/expenses.period') }}: {{ $data['from'] }} {{ __('report/expenses.to') }} {{ $data['to'] }}</p>

    @php $grandTotal = $expenses->sum('amount'); @endphp

    <div class="section-title">{{ __('report/expenses.summary') }}</div>
    <table>
        <tr>
            <th width="25%">{{ __('report/expenses.total_expenses') }}</th>
            <td>{{ $expenses->count() }}</td>
            <th width="25%">{{ __('report/expenses.grand_total') }}</th>
            <td>${{ number_format($grandTotal, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">{{ __('report/expenses.expense_detail') }}</div>
    <table>
        <thead>
            <tr>
                <th>{{ __('report/expenses.expense_number') }}</th>
                <th>{{ __('report/expenses.title') }}</th>
                <th>{{ __('report/expenses.category') }}</th>
                <th>{{ __('report/expenses.vendor') }}</th>
                <th>{{ __('report/expenses.method') }}</th>
                <th>{{ __('report/expenses.date') }}</th>
                <th>{{ __('report/expenses.amount') }}</th>
                <th>{{ __('report/expenses.status') }}</th>
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
                <tr><td colspan="8">{{ __('report/expenses.no_expenses_found') }}</td></tr>
            @endforelse
            <tr class="totals-row">
                <td colspan="6">{{ __('report/expenses.total') }}</td>
                <td>${{ number_format($grandTotal, 2) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
