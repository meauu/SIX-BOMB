<?php
session_start();
include 'config.php';

// Fetch products from database
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Cakes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="#">Pink Ribbon</a>
        <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search Cake" aria-label="Search" id="searchBox" onkeyup="filterProducts()">
            <button class="btn btn-outline-light" type="button">Search</button>
        </form>
    </div>
</nav>

<div class="d-flex">
    <div class="sidebar">
        <a class="nav-link" href="index.php">
            <img src="icons/home.png" alt="Home" width="30" height="30">
        </a>
        <a class="nav-link" href="Login.php">
            <img src="icons/admin.png" alt="Admin" width="30" height="30">
        </a>
        <a class="nav-link" href="#">
            <img src="icons/cart.png" alt="Cart" width="30" height="30">
        </a>
        <a class="nav-link" href="#">
            <img src="icons/list.png" alt="List" width="30" height="30">
        </a>
    </div>

    <div class="content">
        <h2 class="text-center">Order Your Cake</h2>
        <div class="row">
            <div class="col-md-6">
                <h4>Select Cakes</h4>
                <div class="list-group" id="productList">
                    <?php foreach ($products as $product) { ?>
                        <button class="list-group-item list-group-item-action product-item" onclick="selectProduct(<?php echo $product['product_id']; ?>, '<?php echo $product['name']; ?>', <?php echo $product['price']; ?>)">
                            <?php echo $product['name'] . " - ₱" . $product['price']; ?>
                        </button>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-6">
                <h4>Your Order</h4>
                <div id="order-list"></div>
                <h5 id="total-price" class="mt-3">Total: ₱0.00</h5>
                <button onclick="placeOrder()" class="btn btn-primary flex-fill me-2">Place Order</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let orders = [];
    function selectProduct(id, name, price) {
        let existing = orders.find(order => order.product_id === id);
        if (existing) {
            existing.quantity += 1;
        } else {
            orders.push({ product_id: id, name: name, price: price, quantity: 1 });
        }
        updateOrderList();
    }

    function updateQuantity(index, quantity) {
        orders[index].quantity = quantity;
        updateOrderList();
    }

    function removeOrder(index) {
        orders.splice(index, 1);
        updateOrderList();
    }

    function updateOrderList() {
        let orderList = document.getElementById('order-list');
        orderList.innerHTML = "";
        let totalPrice = 0;
        orders.forEach((order, index) => {
            totalPrice += order.price * order.quantity;
            orderList.innerHTML += `
                <div class='d-flex justify-content-between mb-2'>
                    <span>${order.name} (₱${order.price} x ${order.quantity})</span>
                    <div>
                        <input type='number' min='1' value='${order.quantity}' onchange='updateQuantity(${index}, this.value)' class='form-control d-inline w-25'>
                        <button class='btn btn-danger btn-sm' onclick='removeOrder(${index})'>X</button>
                    </div>
                </div>
            `;
        });
        document.getElementById('total-price').innerText = 'Total: ₱' + totalPrice.toFixed(2);
    }

    function placeOrder() {
        localStorage.setItem("orders", JSON.stringify(orders));
        let totalPrice = orders.reduce((sum, order) => sum + (order.price * order.quantity), 0);
        window.location.href = `payment.php?total=${totalPrice}`;
    }

    function filterProducts() {
        let input = document.getElementById('searchBox').value.toLowerCase();
        let products = document.querySelectorAll('.product-item');
        products.forEach(product => {
            let productName = product.innerText.toLowerCase();
            product.style.display = productName.includes(input) ? '' : 'none';
        });
    }
</script>

</body>

<style>
    .sidebar {
        width: 80px;
        background: #343a40;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 20px;
    }
    .sidebar a {
        margin-bottom: 20px;
        transition: transform 0.2s, opacity 0.2s;
    }
    .sidebar a:hover {
        transform: scale(1.2);
        opacity: 0.8;
    }
    .content {
        flex-grow: 1;
        padding: 20px;
    }
</style>
</html>
