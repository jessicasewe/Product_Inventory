<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Product List</h1>
        <form id="productForm">
            @csrf
        
            <input type="hidden" id="product-id" name="product_id">
        
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="productName">Product Name</label>
                    <input type="text" class="form-control" id="productName" name="product_name" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="quantityInStock">Quantity in Stock</label>
                    <input type="number" class="form-control" id="quantityInStock" name="quantity_in_stock" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="pricePerItem">Price per Item</label>
                    <input type="number" step="0.01" class="form-control" id="pricePerItem" name="price_per_item" required>
                </div>
            </div>
        
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <hr>

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

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            function loadData() {
                $.get('/products')
                    .done(function (data) {
                        $('#productTable tbody').empty();
                        data.forEach(function (product) {
                            var totalValue = product.quantity_in_stock * product.price_per_item;
                            var row = '<tr data-id="' + product.id + '">' +
                                '<td>' + product.product_name + '</td>' +
                                '<td>' + product.quantity_in_stock + '</td>' +
                                '<td>$' + product.price_per_item + '</td>' +
                                '<td>' + product.created_at + '</td>' +
                                '<td>$' + totalValue.toFixed(2) + '</td>' +
                                '<td>' +
                                '<button class="btn btn-sm btn-info editProduct">Edit</button> ' +
                                '<button class="btn btn-sm btn-danger deleteProduct">Delete</button>' +
                                '</td>' +
                                '</tr>';
                            $('#productTable tbody').append(row);
                        });
                        updateSumTotal(data);
                        $('#error-message').hide();
                    })
                    .fail(function (xhr, status, error) {
                        console.error('Error loading data:', error);
                        $('#error-message').text('Error loading data. Please try again.').show();
                    });
            }


            function updateSumTotal(products) {
                var totalSum = products.reduce(function(sum, product) {
                    return sum + (product.quantity_in_stock * product.price_per_item);
                }, 0);
                $('#totalValue').text('$' + totalSum.toFixed(2));
            }

            loadData();

            
            $('#productForm').on('submit', function (e) {
                e.preventDefault();
                var productName = $('#productName').val();
                var quantity = $('#quantityInStock').val();
                var price = $('#pricePerItem').val();
                var id = $('#product-id').val(); 

                var url = id ? '/product/' + id : '/products'; 
                var method = id ? 'PUT' : 'POST';

                $.ajax({
                    type: method,
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        product_name: productName,
                        quantity_in_stock: quantity,
                        price_per_item: price
                    },
                    success: function (data) {
                        $('#productForm')[0].reset();
                        $('#product-id').val('');
                        loadData();
                        $('#error-message').hide(); 
                    },
                    error: function (xhr, status, error) {
                        console.error('Error saving product:', error);
                        $('#error-message').text('Error saving product. Please try again.').show();
                    }
                });
            });

            $('#productTable').on('click', '.editProduct', function () {
                var row = $(this).closest('tr');
                var id = row.data('id');
                var productName = row.find('td:eq(0)').text();
                var quantity = row.find('td:eq(1)').text();
                var price = row.find('td:eq(2)').text();

                $('#product-id').val(id);
                $('#productName').val(productName);
                $('#quantityInStock').val(quantity);
                $('#pricePerItem').val(price);
            });

            $('#productTable').on('click', '.deleteProduct', function () {
                var row = $(this).closest('tr');
                var id = row.data('id');

                if (confirm('Are you sure you want to delete this product?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/product/' + id,
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function () {
                            row.remove();
                            loadData();
                        },
                        error: function (xhr, status, error) {
                            console.error('Error deleting product:', error);
                            alert('Error deleting product. Please try again.');
                        }
                    });
                }
            });
        });
    </script>    
</body>
</html>
