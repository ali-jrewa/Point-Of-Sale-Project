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

    <div class="section-title">Summary</div>
    <table>
        <tr>
            <th width="25%">Total Products</th>
            <td>{{ $products->count() }}</td>
            <th width="25%">Stock Value (Cost)</th>
            <td>${{ number_format($totalStockValueCost, 2) }}</td>
        </tr>
        <tr>
            <th>Stock Value (Retail)</th>
            <td>${{ number_format($totalStockValueRetail, 2) }}</td>
            <th></th>
            <td></td>
        </tr>
    </table>

    <div class="section-title">Stock Detail</div>
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Product</th>
                <th>Category</th>
                <th>Stock Qty</th>
                <th>Cost Price</th>
                <th>Retail Price</th>
                <th>Stock Value (Cost)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->sku ?? '-' }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>${{ number_format($product->cost_price, 2) }}</td>
                    <td>${{ number_format($product->retail_price, 2) }}</td>
                    <td>${{ number_format($product->stock_quantity * $product->cost_price, 2) }}</td>
                    <td>{{ ucfirst($product->status->value) }}</td>
                </tr>
            @empty
                <tr><td colspan="8">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
