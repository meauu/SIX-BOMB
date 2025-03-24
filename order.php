<?php
session_start();
include 'config.php';

// Fetch products from database
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Handle order submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orders = json_decode($_POST['orders'], true);
    foreach ($orders as $order) {
        $product_id = $order['product_id'];
        $quantity = $order['quantity'];
        
        // Fetch product price
        $query = "SELECT price FROM products WHERE id = '$product_id' LIMIT 1";
        $result = mysqli_query($conn, $query);
        $product = mysqli_fetch_assoc($result);
        $total_amount = $product['price'] * $quantity;
        
        // Insert order into sales table
        $query = "INSERT INTO sales (product_id, quantity, total_amount, sale_date) VALUES ('$product_id', '$quantity', '$total_amount', NOW())";
        mysqli_query($conn, $query);
    }
    echo "<script>alert('Order placed successfully!'); window.location.href='order.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Cakes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script>
        let orders = [];
        function selectProduct(id, name, price) {
            let existing = orders.find(order => order.product_id === id);
            if (!existing) {
                orders.push({ product_id: id, name: name, price: price, quantity: 1 });
            }
            updateOrderList();
        }

        function updateQuantity(index, quantity) {
            orders[index].quantity = quantity;
            updateOrderList();
        }

        function updateOrderList() {
            let orderList = document.getElementById('order-list');
            orderList.innerHTML = "";
            orders.forEach((order, index) => {
                orderList.innerHTML += `
                    <div class='d-flex justify-content-between mb-2'>
                        <span>\${order.name} (₱\${order.price})</span>
                        <input type='number' min='1' value='\${order.quantity}' onchange='updateQuantity(\${index}, this.value)' class='form-control w-25'>
                    </div>
                `;
            });
        }

        function submitOrder() {
            document.getElementById('orders').value = JSON.stringify(orders);
            document.getElementById('order-form').submit();
        }
    </script>
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Pink Ribbon</a>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center">Order Your Cake</h2>
    <div class="row">
        <div class="col-md-6">
            <h4>Select Cakes</h4>
            <div class="list-group">
                <?php foreach ($products as $product) { ?>
                    <button class="list-group-item list-group-item-action" onclick="selectProduct(<?php echo $product['product_id']; ?>, '<?php echo $product['name']; ?>', <?php echo $product['price']; ?>)">
                        <?php echo $product['name'] . " - ₱" . $product['price']; ?>
                    </button>
                <?php } ?>
            </div>
        </div>
        <div class="col-md-6">
            <h4>Your Order</h4>
            <div id="order-list"></div>
            <form id="order-form" method="POST" action="">
                <input type="hidden" id="orders" name="orders">
                <button type="button" class="btn btn-success w-100 mt-3" onclick="submitOrder()">Place Order</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>