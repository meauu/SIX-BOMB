<?php
include 'config.php';

// Fetch products
$productsQuery = "SELECT * FROM products ORDER BY product_id ASC";
$productsResult = mysqli_query($conn, $productsQuery);

// Handle adding a new product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock_quantity'];

    $insertProduct = "INSERT INTO products (name, price, stock_quantity) VALUES ('$name', $price, $stock)";
    mysqli_query($conn, $insertProduct);

    echo "<script>alert('Product added successfully!'); window.location.href='products.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Pink Ribbon</a>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="text-center">Manage Products</h2>

    <!-- Form to Add a New Product -->
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">Add New Cake</div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="name" class="form-label">Cake Name</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price (₱)</label>
                    <input type="number" class="form-control" name="price" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="stock_quantity" class="form-label">Stock Quantity</label>
                    <input type="number" class="form-control" name="stock_quantity" min="0" required>
                </div>
                <button type="submit" class="btn btn-success">Add Product</button>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="mt-4">
        <h3>Available Cakes</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cake Name</th>
                    <th>Price (₱)</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = mysqli_fetch_assoc($productsResult)) { ?>
                    <tr>
                        <td><?php echo $product['product_id']; ?></td>
                        <td><?php echo $product['name']; ?></td>
                        <td>₱<?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo $product['stock_quantity']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <a href="index.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>