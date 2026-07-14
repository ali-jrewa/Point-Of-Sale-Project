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

    <div class="section-title">Revenue</div>
    <table>
        <tr>
            <th width="50%">Gross Sales Revenue</th>
            <td>${{ number_format($totalRevenue, 2) }}</td>
        </tr>
        <tr>
            <th>Less: Refunds</th>
            <td class="text-danger">-${{ number_format($totalRefunds, 2) }}</td>
        </tr>
        <tr class="totals-row">
            <td>Net Revenue</td>
            <td>${{ number_format($netRevenue, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">Cost of Goods Sold</div>
    <table>
        <tr>
            <th width="50%">Total Purchases (COGS)</th>
            <td class="text-danger">-${{ number_format($totalCogs, 2) }}</td>
        </tr>
        <tr class="totals-row">
            <td>Gross Profit</td>
            <td class="{{ $grossProfit >= 0 ? 'text-success' : 'text-danger' }}">
                ${{ number_format($grossProfit, 2) }}
            </td>
        </tr>
    </table>

    <div class="section-title">Operating Expenses</div>
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expensesByCategory as $category => $amount)
                <tr>
                    <td>{{ $category }}</td>
                    <td>${{ number_format($amount, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="2">No expenses recorded for this period.</td></tr>
            @endforelse
            <tr class="totals-row">
                <td>Total Expenses</td>
                <td class="text-danger">-${{ number_format($totalExpenses, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Net Profit / Loss</div>
    <table>
        <tr class="totals-row">
            <td width="50%">Net {{ $netProfit >= 0 ? 'Profit' : 'Loss' }}</td>
            <td class="{{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}" style="font-size:14px;">
                ${{ number_format(abs($netProfit), 2) }}
            </td>
        </tr>
    </table>

</body>
</html>
