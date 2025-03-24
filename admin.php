<?php
// Connect to the database
include 'config.php';

// Fetch total sales
$salesQuery = "SELECT COUNT(*) AS total_sales, SUM(total_amount) AS total_revenue FROM sales";
$salesResult = mysqli_query($conn, $salesQuery);
$salesData = mysqli_fetch_assoc($salesResult);
$totalSales = $salesData['total_sales'];
$totalRevenue = $salesData['total_revenue'];

// Fetch total products
$productsQuery = "SELECT COUNT(*) AS total_products FROM products";
$productsResult = mysqli_query($conn, $productsQuery);
$productsData = mysqli_fetch_assoc($productsResult);
$totalProducts = $productsData['total_products'];

// Fetch total stock
$stockQuery = "SELECT SUM(stock_quantity) AS total_stock FROM products";
$stockResult = mysqli_query($conn, $stockQuery);
$stockData = mysqli_fetch_assoc($stockResult);
$totalStock = $stockData['total_stock'];

// Fetch product details (cake names, prices, stock)
$cakesQuery = "SELECT name, price, stock_quantity FROM products";
$cakesResult = mysqli_query($conn, $cakesQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Pink Ribbon</a>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="text-center">Point of Sale Dashboard</h2>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Sales</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $totalSales; ?> Transactions</h5>
                    <p class="card-text">Revenue: ₱<?php echo number_format($totalRevenue, 2); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Products</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $totalProducts; ?> Items</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Inventory</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $totalStock; ?> Items in Stock</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Cakes Table -->
    <div class="mt-4">
        <h3>Available Cakes</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price (₱)</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($cake = mysqli_fetch_assoc($cakesResult)) { ?>
                    <tr>
                        <td><?php echo $cake['name']; ?></td>
                        <td><?php echo number_format($cake['price'], 2); ?></td>
                        <td><?php echo $cake['stock_quantity']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <a href="sales.php" class="btn btn-primary">Manage Sales</a>
        <a href="products.php" class="btn btn-success">Manage Products</a>
        <a href="reports.php" class="btn btn-warning">View Reports</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>