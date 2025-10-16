<?php
session_start();
include('../includes/config.php');

// No admin check - accessible to all users

if (isset($_POST['update_item'])) {
    $item_id = $_POST['item_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $cost_price = trim($_POST['cost_price']);
    $sell_price = trim($_POST['sell_price']);
    $quantity = $_POST['quantity'];

    // Validation
    if (empty($description)) {
        $_SESSION['error'] = 'Please input a product description';
        header("Location: edit.php?id=" . $item_id);
        exit();
    }

    if (!is_numeric($cost_price) || $cost_price < 0) {
        $_SESSION['error'] = 'Invalid cost price format';
        header("Location: edit.php?id=" . $item_id);
        exit();
    }

    if (!is_numeric($sell_price) || $sell_price < 0) {
        $_SESSION['error'] = 'Invalid sell price format';
        header("Location: edit.php?id=" . $item_id);
        exit();
    }

    if (!is_numeric($quantity) || $quantity < 0) {
        $_SESSION['error'] = 'Invalid quantity format';
        header("Location: edit.php?id=" . $item_id);
        exit();
    }

    // Handle image upload if new image is provided
    $img_path = null;
    $update_image = false;
    
    if (isset($_FILES['new_img_path']) && $_FILES['new_img_path']['error'] == 0) {
        $file = $_FILES['new_img_path'];
        
        // Validate file type
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
        if (in_array($file['type'], $allowed_types)) {
            // Validate file size (5MB max)
            if ($file['size'] <= 5 * 1024 * 1024) {
                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = 'item_' . $item_id . '_' . time() . '.' . $file_extension;
                $target_path = 'images/' . $new_filename;
                
                if (move_uploaded_file($file['tmp_name'], $target_path)) {
                    $img_path = $target_path;
                    $update_image = true;
                } else {
                    $_SESSION['error'] = 'Failed to upload image';
                    header("Location: edit.php?id=" . $item_id);
                    exit();
                }
            } else {
                $_SESSION['error'] = 'File size too large. Maximum 5MB allowed.';
                header("Location: edit.php?id=" . $item_id);
                exit();
            }
        } else {
            $_SESSION['error'] = 'Invalid file type. Only JPG, JPEG, and PNG files are allowed.';
            header("Location: edit.php?id=" . $item_id);
            exit();
        }
    }

    // Update item - only update image if new one was uploaded
    if ($update_image) {
       date_default_timezone_set('Asia/Manila');
        $updated_at = date('Y-m-d H:i:s');

        $sql = "UPDATE item SET title=?, description=?, cost_price=?, sell_price=?, img_path=?, updated_at=? WHERE item_id=?";
        $stmt = mysqli_prepare($conn, $sql);
       mysqli_stmt_bind_param($stmt, "ssddssi", $title, $description, $cost_price, $sell_price, $img_path, $updated_at, $item_id);

    } else {
        // Don't update img_path if no new image was uploaded
      $sql = "UPDATE item SET title=?, description=?, cost_price=?, sell_price=?, updated_at=? WHERE item_id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssddsi", $title, $description, $cost_price, $sell_price, $updated_at, $item_id);

}
    
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($result) {
        // Update stock quantity
        $stock_sql = "UPDATE stock SET quantity=? WHERE item_id=?";
        $stock_stmt = mysqli_prepare($conn, $stock_sql);
        mysqli_stmt_bind_param($stock_stmt, "ii", $quantity, $item_id);
        $stock_result = mysqli_stmt_execute($stock_stmt);
        mysqli_stmt_close($stock_stmt);

        if ($stock_result) {
            $_SESSION['success'] = 'Item updated successfully!';
        } else {
            $_SESSION['error'] = 'Item updated but failed to update stock quantity.';
        }
    } else {
        $_SESSION['error'] = 'Failed to update item.';
    }

    header("Location: ../index.php");
    exit();
} else {
    header("Location: ../index.php");
    exit();
}
?>