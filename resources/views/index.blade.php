<!-- resources/views/product/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Product List</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Product List</h1>
    
        <!-- Form for data submission -->
        <form id="productForm">
            @csrf <!-- Laravel CSRF protection token -->
    
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="productName">Product Name</label>
                    <input type="text" class="form-control" id="productName" name="productName" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="quantityInStock">Quantity in Stock</label>
                    <input type="number" class="form-control" id="quantityInStock" name="quantityInStock" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="pricePerItem">Price per Item</label>
                    <input type="number" step="0.01" class="form-control" id="pricePerItem" name="pricePerItem" required>
                </div>
            </div>
    
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    
        <hr>
    
        <!-- Product List Table -->
        <table id="productTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity in Stock</th>
                    <th>Price per Item</th>
                    <th>Datetime Submitted</th>
                    <th>Total Value</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr data-id="{{ $product->id }}">
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->quantity_in_stock }}</td>
                    <td>${{ $product->price_per_item }}</td>
                    <td>{{ $product->created_at }}</td>
                    <td>${{ $product->quantity_in_stock * $product->price_per_item }}</td>
                    <td>
                        <button class="btn btn-sm btn-info editProduct">Edit</button>
                        <button class="btn btn-sm btn-danger deleteProduct">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    
        <div class="text-right">
            <strong>Total:</strong> <span id="totalValue">${{ $products->sum(fn($product) => $product->quantity_in_stock * $product->price_per_item) }}</span>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Submit form via AJAX
        $('#productForm').submit(function(event) {
            event.preventDefault();
    
            var formData = $(this).serialize();
    
            $.ajax({
                url: '/save-product', // Replace with your Laravel route
                type: 'POST',
                data: formData,
                success: function(response) {
                    console.log(response);
    
                    // Update table with new product data
                    var newRow = `<tr data-id="${response.id}">
                                    <td>${response.product_name}</td>
                                    <td>${response.quantity_in_stock}</td>
                                    <td>$${response.price_per_item}</td>
                                    <td>${response.created_at}</td>
                                    <td>$${response.quantity_in_stock * response.price_per_item}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info editProduct">Edit</button>
                                        <button class="btn btn-sm btn-danger deleteProduct">Delete</button>
                                    </td>
                                  </tr>`;
                    
                    $('#productTable tbody').append(newRow);
    
                    // Update total value
                    var total = parseFloat($('#totalValue').text().replace('$', '')) + (response.quantity_in_stock * response.price_per_item);
                    $('#totalValue').text('$' + total.toFixed(2));
    
                    // Clear form inputs
                    $('#productName').val('');
                    $('#quantityInStock').val('');
                    $('#pricePerItem').val('');
                },
                error: function(error) {
                    console.log(error);
                    alert('Error saving product. Please try again.');
                }
            });
        });
    });
    </script>    
</body>
</html>
