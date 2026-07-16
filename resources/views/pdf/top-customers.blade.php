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
        <p>Generated on: {{ $data['date'] }}</p>
    </div>

    <p class="range">Period: {{ $data['from'] }} to {{ $data['to'] }}</p>

    <div class="section-title">Top 20 Customers by Spend</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Total Orders</th>
                <th>Total Spend</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $index => $customer)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->sales_count }}</td>
                    <td class="text-success">${{ number_format($customer->sales_sum_total, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No customer sales found for this period.</td></tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
