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

    <div class="section-title">Products Requiring Reorder</div>
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Product</th>
                <th>Category</th>
                <th>Current Stock</th>
                <th>Low Stock Threshold</th>
                <th>Shortfall</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->sku ?? '-' }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td class="text-danger">{{ $product->stock_quantity }}</td>
                    <td>{{ $product->low_stock_threshold }}</td>
                    <td class="text-danger">{{ max(0, $product->low_stock_threshold - $product->stock_quantity) }}</td>
                </tr>
            @empty
                <tr><td colspan="6">No low stock products. Everything is well stocked.</td></tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
