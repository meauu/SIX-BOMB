<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from form
    $order_data = isset($_POST['order_data']) ? json_decode($_POST['order_data'], true) : [];
    $total_price = isset($_POST['total_price']) ? floatval($_POST['total_price']) : 0;
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : "Unknown";

    // Debugging - Show error if no order data received
    if (empty($order_data)) {
        die("<script>alert('Error: No order data received! Please try again.'); window.location.href='payment.php';</script>");
    }
    if ($total_price <= 0) {
        die("<script>alert('Error: Invalid total price!'); window.location.href='payment.php';</script>");
    }

    // Begin transaction to avoid partial orders
    mysqli_begin_transaction($conn);
    try {
        foreach ($order_data as $order) {
            if (!isset($order['product_id'], $order['quantity'], $order['price'])) {
                throw new Exception("Error: Missing order details.");
            }

            $product_id = intval($order['product_id']);
            $quantity = intval($order['quantity']);
            $price = floatval($order['price']);
            $subtotal = $price * $quantity;

            // Insert into sales table
            $query = "INSERT INTO sales (product_id, quantity, total_amount, payment_method, sale_date) 
                      VALUES ('$product_id', '$quantity', '$subtotal', '$payment_method', NOW())";

            if (!mysqli_query($conn, $query)) {
                throw new Exception("Error: Failed to insert order.");
            }
        }

        // Commit transaction if successful
        mysqli_commit($conn);

        // Clear cart and redirect
        echo "<script>
                alert('Payment successful! Thank you for your purchase.');
                localStorage.removeItem('orders');
                window.location.href='order_confirmation.php';
              </script>";
        exit;
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        echo "<script>alert('{$e->getMessage()}'); window.location.href='payment.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='payment.php';</script>";
    exit;
}
?>
