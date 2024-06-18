<!-- resources/views/product/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Product List</title>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Product List</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity in Stock</th>
                <th>Price per Item</th>
                <th>Datetime Submitted</th>
                <th>Total Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->quantity_in_stock }}</td>
                <td>${{ $product->price_per_item }}</td>
                <td>{{ $product->created_at }}</td>
                <td>${{ $product->quantity_in_stock * $product->price_per_item }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-right"><strong>Total:</strong></td>
                <td><strong>${{ $products->sum(fn($product) => $product->quantity_in_stock * $product->price_per_item) }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>
