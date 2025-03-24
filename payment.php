<?php
session_start();
include 'config.php';

$total_amount = isset($_GET['total']) ? $_GET['total'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Payment</h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Confirm Your Payment</h4>
                </div>
                <div class="card-body">
                    <h5>Total Amount: <span id="total-amount">₱<?php echo number_format($total_amount, 2); ?></span></h5>
                    <form method="POST" action="process_payment.php">
                        <input type="hidden" id="orders" name="orders">
                        <input type="hidden" name="total" value="<?php echo $total_amount; ?>">
                        <label for="payment-method">Select Payment Method:</label>
                        <select class="form-control mb-3" name="payment_method" required>
                            <option value="Cash">Cash</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Gcash">Gcash</option>
                        </select>
                        <button type="submit" class="btn btn-success w-100">Proceed to Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let orders = localStorage.getItem("orders");
    document.getElementById("orders").value = orders;
</script>

</body>
</html>
