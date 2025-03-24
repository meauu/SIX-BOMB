<?php
include 'config.php';

// Fetch sales transactions
$salesQuery = "SELECT sales.id, products.name AS cake_name, sales.quantity, sales.total_amount, sales.sale_date 
               FROM sales 
               INNER JOIN products ON sales.product_id = products.product_id 
               ORDER BY sales.sale_date DESC";
$salesResult = mysqli_query($conn, $salesQuery);

// Fetch cakes for dropdown selection
$productsQuery = "SELECT product_id, name, price, stock_quantity FROM products";
$productsResult = mysqli_query($conn, $productsQuery);

// Handle new sale submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Get product price & stock
    $productQuery = "SELECT price, stock_quantity FROM products WHERE product_id = $product_id";
    $productResult = mysqli_query($conn, $productQuery);
    $product = mysqli_fetch_assoc($productResult);

    if ($product && $quantity > 0 && $quantity <= $product['stock_quantity']) {
        $total_amount = $product['price'] * $quantity;

        // Insert into sales table
        $insertSale = "INSERT INTO sales (product_id, quantity, total_amount, sale_date) VALUES 
                      ($product_id, $quantity, $total_amount, NOW())";
        mysqli_query($conn, $insertSale);

        // Update stock
        $updateStock = "UPDATE products SET stock_quantity = stock_quantity - $quantity WHERE product_id = $product_id";
        mysqli_query($conn, $updateStock);

        echo "<script>alert('Sale recorded successfully!'); window.location.href='sales.php';</script>";
    } else {
        echo "<script>alert('Invalid quantity or insufficient stock!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Pink Ribbon</a>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="text-center">Manage Sales</h2>

    <!-- Form to Record a New Sale -->
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">Record New Sale</div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="product_id" class="form-label">Select Cake</label>
                    <select class="form-select" name="product_id" required>
                        <option value="" disabled selected>Choose a cake...</option>
                        <?php while ($product = mysqli_fetch_assoc($productsResult)) { ?>
                            <option value="<?php echo $product['id']; ?>">
                                <?php echo $product['name']; ?> (₱<?php echo number_format($product['price'], 2); ?>)
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" name="quantity" min="1" required>
                </div>
                <button type="submit" class="btn btn-success">Add Sale</button>
            </form>
        </div>
    </div>

    <!-- Sales Transactions Table -->
    <div class="mt-4">
        <h3>Sales Transactions</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Cake Name</th>
                    <th>Quantity</th>
                    <th>Total Amount (₱)</th>
                    <th>Sale Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($sale = mysqli_fetch_assoc($salesResult)) { ?>
                    <tr>
                        <td><?php echo $sale['cake_name']; ?></td>
                        <td><?php echo $sale['quantity']; ?></td>
                        <td>₱<?php echo number_format($sale['total_amount'], 2); ?></td>
                        <td><?php echo $sale['sale_date']; ?></td>
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