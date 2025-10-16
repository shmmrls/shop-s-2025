<?php
session_start();
include('../includes/config.php');

// No admin check - accessible to all users

if (isset($_POST['submit'])) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description']);
    $cost_price = trim($_POST['cost_price']);
    $sell_price = trim($_POST['sell_price']);
    $quantity = $_POST['quantity'];
    $img_path = '';

    // Validation
    if (empty($description)) {
        $_SESSION['descError'] = 'Please input a product description';
        header("Location: create.php");
        exit();
    }

    if (empty($cost_price) || !is_numeric($cost_price) || $cost_price < 0) {
        $_SESSION['costError'] = 'Invalid cost price format';
        header("Location: create.php");
        exit();
    }

    if (empty($sell_price) || !is_numeric($sell_price) || $sell_price < 0) {
        $_SESSION['sellError'] = 'Invalid sell price format';
        header("Location: create.php");
        exit();
    }

    if (!is_numeric($quantity) || $quantity < 0) {
        $_SESSION['qtyError'] = 'Invalid quantity format';
        header("Location: create.php");
        exit();
    }

    // Handle image upload
    if (isset($_FILES['img_path']) && $_FILES['img_path']['error'] == 0) {
        $file = $_FILES['img_path'];
        
        // Validate file type
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
        if (in_array($file['type'], $allowed_types)) {
            // Validate file size (5MB max)
            if ($file['size'] <= 5 * 1024 * 1024) {
                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = 'item_' . time() . '_' . uniqid() . '.' . $file_extension;
                $target = 'images/' . $new_filename;
                
                if (move_uploaded_file($file['tmp_name'], $target)) {
                    $img_path = $target;
                } else {
                    $_SESSION['imageError'] = 'Failed to upload image';
                    header("Location: create.php");
                    exit();
                }
            } else {
                $_SESSION['imageError'] = 'File size too large. Maximum 5MB allowed.';
                header("Location: create.php");
                exit();
            }
        } else {
            $_SESSION['imageError'] = 'Invalid file type. Only JPG, JPEG, and PNG files are allowed.';
            header("Location: create.php");
            exit();
        }
    } else {
        $_SESSION['imageError'] = 'Please select an image file';
        header("Location: create.php");
        exit();
    }

    // Insert item using prepared statement
     date_default_timezone_set('Asia/Manila'); // set timezone
        $created_at = date('Y-m-d H:i:s');
        $updated_at = $created_at;

        $sql = "INSERT INTO item (title, description, cost_price, sell_price, img_path, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssddsss", $title, $description, $cost_price, $sell_price, $img_path, $created_at, $updated_at);

    $result = mysqli_stmt_execute($stmt);
    $item_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    if ($result) {
        // Insert stock quantity
       
        $stock_sql = "INSERT INTO stock(item_id, quantity) VALUES(?, ?)";
       
        $stock_stmt = mysqli_prepare($conn, $stock_sql);
        mysqli_stmt_bind_param($stock_stmt, "ii", $item_id, $quantity);
        $stock_result = mysqli_stmt_execute($stock_stmt);
        mysqli_stmt_close($stock_stmt);

        if ($stock_result) {
            $_SESSION['success'] = 'Item created successfully!';
        } else {
            $_SESSION['error'] = 'Item created but failed to add stock quantity.';
        }
    } else {
        $_SESSION['error'] = 'Failed to create item.';
    }

    header("Location: ../index.php");
    exit();
} else {
    header("Location: create.php");
    exit();
}