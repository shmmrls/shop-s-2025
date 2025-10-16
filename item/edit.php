<?php
session_start();
include('../includes/header.php');
include('../includes/config.php');

// No admin check - accessible to all users

// Get item data
$item = null;
if (isset($_GET['id'])) {
    $item_id = $_GET['id'];
    $sql = "SELECT i.*, s.quantity FROM item i LEFT JOIN stock s ON i.item_id = s.item_id WHERE i.item_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $item_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $item = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

if (!$item) {
    $_SESSION['error'] = 'Item not found';
    header("Location: index.php");
    exit();
}
?>

<body>
    <div class="container">
        <h2>Edit Item</h2>
        <form method="POST" action="update.php" enctype="multipart/form-data">
            <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
            
            <div class="form-group mb-3">
                <label for="title">Item Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($item['title'] ?? ''); ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="description">Item Description</label>
                <input type="text" class="form-control" id="description" name="description" value="<?php echo htmlspecialchars($item['description']); ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="cost_price">Cost Price</label>
                <input type="number" step="0.01" class="form-control" id="cost_price" name="cost_price" value="<?php echo $item['cost_price']; ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="sell_price">Sell Price</label>
                <input type="number" step="0.01" class="form-control" id="sell_price" name="sell_price" value="<?php echo $item['sell_price']; ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="quantity">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $item['quantity'] ?? 0; ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="img_path">Current Image</label>
                <div class="mb-2">
                    <img src="<?php echo $item['img_path']; ?>" width="150" height="150" class="img-thumbnail">
                </div>
                <label for="new_img_path">Upload New Image (optional)</label>
                <input type="file" class="form-control" id="new_img_path" name="new_img_path" accept="image/jpeg,image/jpg,image/png">
                <small class="text-muted">Leave empty to keep current image. JPG, JPEG, PNG only.</small>
            </div>

            <button type="submit" class="btn btn-primary" name="update_item">Update Item</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <?php include('../includes/footer.php'); ?>
</body>
</html>
