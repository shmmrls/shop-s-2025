<?php
session_start();
include('../includes/config.php');
// No admin check - accessible to all users
if (isset($_GET['id'])) {
    $item_id = $_GET['id'];
    
    // Get item image path to delete the file
    $img_sql = "SELECT img_path FROM item WHERE item_id = ?";
    $img_stmt = mysqli_prepare($conn, $img_sql);
    mysqli_stmt_bind_param($img_stmt, "i", $item_id);
    mysqli_stmt_execute($img_stmt);
    $img_result = mysqli_stmt_get_result($img_stmt);
    $img_row = mysqli_fetch_assoc($img_result);
    mysqli_stmt_close($img_stmt);
    
    // Delete from stock table first (foreign key constraint)
    $stock_sql = "DELETE FROM stock WHERE item_id = ?";
    $stock_stmt = mysqli_prepare($conn, $stock_sql);
    mysqli_stmt_bind_param($stock_stmt, "i", $item_id);
    $stock_result = mysqli_stmt_execute($stock_stmt);
    mysqli_stmt_close($stock_stmt);
    
    // Delete from item table
    date_default_timezone_set('Asia/Manila');
    $deleted_at = date('Y-m-d H:i:s');

    $item_sql = "UPDATE item SET deleted_at=? WHERE item_id=?";
    $item_stmt = mysqli_prepare($conn, $item_sql);
    mysqli_stmt_bind_param($item_stmt, "si", $deleted_at, $item_id);
    $item_result = mysqli_stmt_execute($item_stmt);

    mysqli_stmt_close($item_stmt);
    
    if ($item_result && $stock_result) {
        // Delete the image file if it exists
        if ($img_row && !empty($img_row['img_path']) && file_exists($img_row['img_path'])) {
            unlink($img_row['img_path']);
        }
        $_SESSION['success'] = 'Item deleted successfully!';
    } else {
        $_SESSION['error'] = 'Failed to delete item.';
    }
} else {
    $_SESSION['error'] = 'Invalid item ID.';
}
header("Location: ../index.php");  // CHANGED
exit();
?>