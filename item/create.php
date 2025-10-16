<?php
session_start();
include('../includes/header.php');
include('../includes/config.php');

// No admin check - accessible to all users
?>

<body>
    <div class="container">
        <form method="POST" action="store.php" enctype="multipart/form-data">
            <div class="form-group mb-3">
                <label for="title">Item Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Enter item title" required>
            </div>

            <div class="form-group mb-3">
                <label for="description">Item Description</label>
                <input type="text" class="form-control" id="description" name="description" placeholder="Enter item description" required>
                <small class="text-danger">
                    <?php
                    if (isset($_SESSION['descError'])) {
                        echo $_SESSION['descError'];
                        unset($_SESSION['descError']);
                    }
                    ?>
                </small>
            </div>

            <div class="form-group mb-3">
                <label for="cost_price">Cost Price</label>
                <input type="number" step="0.01" class="form-control" id="cost_price" name="cost_price" placeholder="Enter cost price" required>
                <small class="text-danger">
                    <?php
                    if (isset($_SESSION['costError'])) {
                        echo $_SESSION['costError'];
                        unset($_SESSION['costError']);
                    }
                    ?>
                </small>
            </div>

            <div class="form-group mb-3">
                <label for="sell_price">Sell Price</label>
                <input type="number" step="0.01" class="form-control" id="sell_price" name="sell_price" placeholder="Enter sell price" required>
                <small class="text-danger">
                    <?php
                    if (isset($_SESSION['sellError'])) {
                        echo $_SESSION['sellError'];
                        unset($_SESSION['sellError']);
                    }
                    ?>
                </small>
            </div>

            <div class="form-group mb-3">
                <label for="quantity">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity" min="0" required>
                <small class="text-danger">
                    <?php
                    if (isset($_SESSION['qtyError'])) {
                        echo $_SESSION['qtyError'];
                        unset($_SESSION['qtyError']);
                    }
                    ?>
                </small>
            </div>

            <div class="form-group mb-3">
                <label for="img_path">Item Image</label>
                <input type="file" class="form-control" id="img_path" name="img_path" accept="image/jpeg,image/jpg,image/png" required>
                <small class="text-muted">JPG, JPEG, PNG only. Maximum 5MB.</small>
                <small class="text-danger">
                    <?php
                    if (isset($_SESSION['imageError'])) {
                        echo $_SESSION['imageError'];
                        unset($_SESSION['imageError']);
                    }
                    ?>
                </small>
            </div>

            <button type="submit" class="btn btn-primary" name="submit">Create Item</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <?php
    include('../includes/footer.php');
    ?>