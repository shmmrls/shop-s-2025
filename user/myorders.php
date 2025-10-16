<?php
session_start();
include('../includes/header.php');
include('../includes/config.php');

// Check if user is logged in
if (!isset($_SESSION['userId'])) {
    $_SESSION['error'] = 'Please login to view your orders';
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['userId'];

// Handle cart operations
if (isset($_POST['update_cart'])) {
    $item_id = $_POST['item_id'];
    $new_quantity = $_POST['quantity'];
    
    if ($new_quantity <= 0) {
        unset($_SESSION['cart'][$item_id]);
    } else {
        $_SESSION['cart'][$item_id]['quantity'] = $new_quantity;
    }
    $_SESSION['success'] = 'Cart updated!';
    header("Location: myorders.php");
    exit();
}

if (isset($_POST['remove_item'])) {
    $item_id = $_POST['item_id'];
    unset($_SESSION['cart'][$item_id]);
    $_SESSION['success'] = 'Item removed from cart!';
    header("Location: myorders.php");
    exit();
}

if (isset($_POST['place_order'])) {
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        // Create order
        $order_sql = "INSERT INTO orderinfo (customer_id, date_placed, status) VALUES (?, CURDATE(), 'Processing')";
        $order_stmt = mysqli_prepare($conn, $order_sql);
        mysqli_stmt_bind_param($order_stmt, "i", $user_id);
        mysqli_stmt_execute($order_stmt);
        $order_id = mysqli_insert_id($conn);
        mysqli_stmt_close($order_stmt);
        
        // Add order items
        $total_amount = 0;
        foreach ($_SESSION['cart'] as $item) {
            $orderline_sql = "INSERT INTO orderline (orderinfo_id, item_id, quantity) VALUES (?, ?, ?)";
            $orderline_stmt = mysqli_prepare($conn, $orderline_sql);
            mysqli_stmt_bind_param($orderline_stmt, "iii", $order_id, $item['item_id'], $item['quantity']);
            mysqli_stmt_execute($orderline_stmt);
            mysqli_stmt_close($orderline_stmt);
            
            // Update stock
            $stock_sql = "UPDATE stock SET quantity = quantity - ? WHERE item_id = ?";
            $stock_stmt = mysqli_prepare($conn, $stock_sql);
            mysqli_stmt_bind_param($stock_stmt, "ii", $item['quantity'], $item['item_id']);
            mysqli_stmt_execute($stock_stmt);
            mysqli_stmt_close($stock_stmt);
            
            $total_amount += $item['price'] * $item['quantity'];
        }
        
        // Clear cart
        unset($_SESSION['cart']);
        $_SESSION['success'] = 'Order placed successfully! Order ID: ' . $order_id;
        header("Location: myorders.php");
        exit();
    }
}

// Order history removed - only showing current cart
?>

<div class="container mt-4">
    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <h2>My Orders</h2>

    <!-- Current Cart -->
    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h4>Current Cart</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $cart_total = 0;
                            foreach ($_SESSION['cart'] as $item): 
                                $item_total = $item['price'] * $item['quantity'];
                                $cart_total += $item_total;
                            ?>
                                <tr>
                                    <td><img src="<?php echo $item['img_path']; ?>" width="50" height="50" class="img-thumbnail"></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($item['title'] ?: 'No Title'); ?></strong><br>
                                        <small><?php echo htmlspecialchars($item['description']); ?></small>
                                    </td>
                                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                                    <td>
                                        <form method="POST" class="d-inline">
                                            <div class="input-group" style="width: 120px;">
                                                <input type="number" class="form-control form-control-sm" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="99">
                                                <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                                                <button class="btn btn-outline-primary btn-sm" type="submit" name="update_cart">Update</button>
                                            </div>
                                        </form>
                                    </td>
                                    <td>$<?php echo number_format($item_total, 2); ?></td>
                                    <td>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                                            <button class="btn btn-outline-danger btn-sm" type="submit" name="remove_item" onclick="return confirm('Remove this item?')">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-primary">
                                <th colspan="4">Total</th>
                                <th>$<?php echo number_format($cart_total, 2); ?></th>
                                <th>
                                    <form method="POST" class="d-inline">
                                        <button class="btn btn-success" type="submit" name="place_order" onclick="return confirm('Place this order?')">Place Order</button>
                                    </form>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <h5>Your cart is empty</h5>
            <a href="../index.php" class="btn btn-primary">Continue Shopping</a>
        </div>
    <?php endif; ?>

</div>

<?php include('../includes/footer.php'); ?>
