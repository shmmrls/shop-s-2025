<?php
session_start();

include('./includes/header.php');
include('./includes/config.php');

// Handle adding items to cart
if (isset($_POST['add_to_cart'])) {
    // Check if user is logged in
    if (!isset($_SESSION['userId'])) {
        $_SESSION['error'] = 'Please log in first to add items to cart.';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
    
    $item_id = $_POST['item_id'];
    $quantity = $_POST['item_qty'];
    
    // Get item details
    $item_sql = "SELECT i.*, s.quantity as stock_qty FROM item i LEFT JOIN stock s ON i.item_id = s.item_id WHERE i.item_id = ?";
    $item_stmt = mysqli_prepare($conn, $item_sql);
    mysqli_stmt_bind_param($item_stmt, "i", $item_id);
    mysqli_stmt_execute($item_stmt);
    $item_result = mysqli_stmt_get_result($item_stmt);
    $item = mysqli_fetch_assoc($item_result);
    mysqli_stmt_close($item_stmt);
    
    if ($item && $quantity > 0 && $quantity <= $item['stock_qty']) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        if (isset($_SESSION['cart'][$item_id])) {
            $_SESSION['cart'][$item_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$item_id] = [
                'item_id' => $item['item_id'],
                'title' => $item['title'],
                'description' => $item['description'],
                'price' => $item['sell_price'],
                'img_path' => $item['img_path'],
                'quantity' => $quantity
            ];
        }
        $_SESSION['success'] = 'Item added to cart!';
    } else {
        $_SESSION['error'] = 'Invalid quantity or item not available.';
    }
}

// unset($_SESSION['cart_products']);
print_r($_SESSION);

// Create Item Button at Top
echo '<div class="container mt-4">';

// Success/Error Messages
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    echo $_SESSION['success'];
    unset($_SESSION['success']);
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    echo '</div>';
}

if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
    echo $_SESSION['error'];
    unset($_SESSION['error']);
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    echo '</div>';
}

/// Check if user is logged in for Add Item button (centered)
echo '<div class="text-center mb-3">';
if (isset($_SESSION['userId'])) {
    echo '<a href="item/create.php" class="btn btn-primary btn-lg" role="button">Add Item</a>';
} else {
    echo '<button type="button" class="btn btn-primary btn-lg" onclick="alert(\'Please log in first to add items.\')">Add Item</button>';
}
echo '</div>';


// Cart Summary
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    echo '<div class="alert alert-info">';
    echo '<h5>Your Cart (' . count($_SESSION['cart']) . ' items)</h5>';
    echo '<a href="user/myorders.php" class="btn btn-primary">View Cart & Checkout</a>';
    echo '</div>';
}
// Optional search
$keyword = '';
if (isset($_GET['search']) && strlen(trim($_GET['search'])) > 0) {
    $keyword = trim($_GET['search']);
}

if ($keyword !== '') {
    $sql = "SELECT i.item_id AS itemId, i.description, i.img_path, i.sell_price FROM item i INNER JOIN stock s USING (item_id) WHERE i.description LIKE ? ORDER BY i.item_id ASC";
    $stmt = mysqli_prepare($conn, $sql);
    $like = "%{$keyword}%";
    mysqli_stmt_bind_param($stmt, 's', $like);
    mysqli_stmt_execute($stmt);
    $results = mysqli_stmt_get_result($stmt);
} else {
    $sql = "SELECT i.item_id AS itemId, description, img_path, sell_price FROM item i INNER JOIN stock s USING (item_id)  ORDER BY i.item_id ASC";
    $results = mysqli_query($conn, $sql);
}

// Check if user is logged in
$is_logged_in = isset($_SESSION['userId']);

if ($results) {
    $products_item = '<ul class="products">';

    //fetch results set as object and output HTML
    while ($row = mysqli_fetch_assoc($results)) {
        // Generate different onclick handlers based on login status
        if ($is_logged_in) {
            $add_onclick = '';
            $edit_onclick = '';
            $delete_onclick = 'return confirm(\'Are you sure you want to delete this item?\')';
        } else {
            $add_onclick = 'alert(\'Please log in first to add items to cart.\'); return false;';
            $edit_onclick = 'alert(\'Please log in first to edit items.\'); return false;';
            $delete_onclick = 'alert(\'Please log in first to delete items.\'); return false;';
        }
        
        $products_item .= <<<EOT
     <li class="product">
     <form method="POST" action="">
    <div class="product-content"><h3>{$row['description']}</h3>
    <div class="product-thumb"><img src="./item/{$row['img_path']}" width="50px" height="50px"></div>
    <div class="product-info">
    Price {$row['sell_price']} 
    <fieldset>
    
    <label>
        <span>Quantity</span>
        <input type="number" size="2" maxlength="2" name="item_qty" value="1" />
    </label>
    </fieldset>
     <input type="hidden" name="item_id" value="{$row['itemId']}" />
     
     <div align="center">
         <button type="submit" name="add_to_cart" class="add_to_cart" style="width: 60px; font-size: 12px;" onclick="{$add_onclick}">Add</button>
         <a href="item/edit.php?id={$row['itemId']}" class="btn btn-outline-primary btn-sm ms-1" title="Edit" style="width: 30px; height: 30px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" onclick="{$edit_onclick}">
             <i class="fa fa-edit" style="font-size: 12px;"></i>
         </a>
         <a href="item/delete.php?id={$row['itemId']}" class="btn btn-outline-danger btn-sm ms-1" title="Delete" onclick="{$delete_onclick}" style="width: 30px; height: 30px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
             <i class="fa fa-trash" style="font-size: 12px;"></i>
         </a>
     </div>
     </div></div>
     </form>
     </li>
EOT;
    }

    $products_item .= '</ul>';
    echo $products_item;
}
echo '</div>';
include('./includes/footer.php');