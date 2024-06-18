$(document).ready(function () {
    function loadData() {
        $.get('/', function (data) {
            $('#product-data').empty();
            $('#product-data').append($(data).find('#product-data').html());
            updateSumTotal();
        });
    }

    function updateSumTotal() {
        let totalSum = 0;
        $('#product-data tr').each(function () {
            totalSum += parseFloat($(this).find('td').eq(4).text());
        });
        $('#sum-total').text(totalSum);
    }

    $('#product-form').on('submit', function (e) {
        e.preventDefault();
        var productName = $('#productName').val();
        var quantity = $('#quantity').val();
        var price = $('#price').val();

        $.ajax({
            type: 'POST',
            url: '/product',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                product_name: productName,
                quantity_in_stock: quantity,
                price_per_item: price
            },
            success: function () {
                $('#product-form')[0].reset();
                loadData();
            }
        });
    });

    $('#product-data').on('click', '.btn-edit', function () {
        var row = $(this).closest('tr');
        var id = row.data('id');
        var productName = row.children().eq(0).text();
        var quantity = row.children().eq(1).text();
        var price = row.children().eq(2).text();

        $('#productName').val(productName);
        $('#quantity').val(quantity);
        $('#price').val(price);

        $('#product-form').off('submit').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                type: 'PUT',
                url: '/product/' + id,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    product_name: productName,
                    quantity_in_stock: quantity,
                    price_per_item: price
                },
                success: function () {
                    $('#product-form')[0].reset();
                    $('#product-form').off('submit').on('submit', function (e) {
                        e.preventDefault();
                        $.ajax({
                            type: 'POST',
                            url: '/product',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                product_name: $('#productName').val(),
                                quantity_in_stock: $('#quantity').val(),
                                price_per_item: $('#price').val()
                            },
                            success: function () {
                                $('#product-form')[0].reset();
                                loadData();
                            }
                        });
                    });
                    loadData();
                }
            });
        });
    });
});
