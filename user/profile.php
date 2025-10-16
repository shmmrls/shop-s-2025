<?php
session_start();
include("../includes/header.php");
include("../includes/config.php");

// Get current user data
$current_user = null;
$customer_data = null;
if (isset($_SESSION['userId'])) {
    $user_id = $_SESSION['userId'];
    $user_sql = "SELECT * FROM users WHERE user_id = ?";
    $user_stmt = mysqli_prepare($conn, $user_sql);
    mysqli_stmt_bind_param($user_stmt, "i", $user_id);
    mysqli_stmt_execute($user_stmt);
    $user_result = mysqli_stmt_get_result($user_stmt);
    $current_user = mysqli_fetch_assoc($user_result);
    mysqli_stmt_close($user_stmt);
    
    // Get customer data if exists
    $customer_sql = "SELECT * FROM customer WHERE user_id = ?";
    $customer_stmt = mysqli_prepare($conn, $customer_sql);
    mysqli_stmt_bind_param($customer_stmt, "i", $user_id);
    mysqli_stmt_execute($customer_stmt);
    $customer_result = mysqli_stmt_get_result($customer_stmt);
    $customer_data = mysqli_fetch_assoc($customer_result);
    mysqli_stmt_close($customer_stmt);
}

// Handle profile data submission
if (isset($_POST['submit'])) {
    $lname = trim($_POST['lname']);
    $fname = trim($_POST['fname']);
    $title = trim($_POST['title']);
    $address = trim($_POST['address']);
    $town = trim($_POST['town']);
    $zipcode = trim($_POST['zipcode']);
    $phone = trim($_POST['phone']);
    $user_id = $_SESSION['userId'];

    if ($customer_data) {
        // Update existing customer data
        $sql = "UPDATE customer SET title=?, lname=?, fname=?, addressline=?, town=?, zipcode=?, phone=? WHERE user_id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssssi", $title, $lname, $fname, $address, $town, $zipcode, $phone, $user_id);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        // Insert new customer data
        $sql = "INSERT INTO customer (user_id, title, lname, fname, addressline, town, zipcode, phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "isssssss", $user_id, $title, $lname, $fname, $address, $town, $zipcode, $phone);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    if ($result) {
        $_SESSION['success'] = 'Profile updated successfully!';
        header("Location: profile.php");
        exit();
    } else {
        $_SESSION['error'] = 'Failed to update profile.';
    }
}

// Handle user account update
if (isset($_POST['update_user'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $user_id = $_SESSION['userId'];

    $sql = "UPDATE users SET name=?, email=? WHERE user_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $name, $email, $user_id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($result) {
        $_SESSION['success'] = 'Account information updated successfully!';
        $_SESSION['email'] = $email; // Update session email
        header("Location: profile.php");
        exit();
    } else {
        $_SESSION['error'] = 'Failed to update account information.';
    }
}

// Handle account deletion
if (isset($_POST['delete_account'])) {
    $user_id = $_SESSION['userId'];
    
    // Delete customer data first
    $customer_sql = "DELETE FROM customer WHERE user_id = ?";
    $customer_stmt = mysqli_prepare($conn, $customer_sql);
    mysqli_stmt_bind_param($customer_stmt, "i", $user_id);
    mysqli_stmt_execute($customer_stmt);
    mysqli_stmt_close($customer_stmt);
    
    // Delete user account
    $user_sql = "DELETE FROM users WHERE user_id = ?";
    $user_stmt = mysqli_prepare($conn, $user_sql);
    mysqli_stmt_bind_param($user_stmt, "i", $user_id);
    $result = mysqli_stmt_execute($user_stmt);
    mysqli_stmt_close($user_stmt);
    
    if ($result) {
        session_destroy();
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['error'] = 'Failed to delete account.';
    }
}

// Handle image upload
if (isset($_POST['upload_image'])) {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $file = $_FILES['profile_image'];
        
        // Validate file type
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
        if (in_array($file['type'], $allowed_types)) {
            // Validate file size (5MB max)
            if ($file['size'] <= 5 * 1024 * 1024) {
                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = 'user_' . $_SESSION['userId'] . '_' . time() . '.' . $file_extension;
                $target_path = 'images/' . $new_filename;
                
                if (move_uploaded_file($file['tmp_name'], $target_path)) {
                    // Update user's img_path in database
                    $update_sql = "UPDATE users SET img_path = ? WHERE user_id = ?";
                    $update_stmt = mysqli_prepare($conn, $update_sql);
                    mysqli_stmt_bind_param($update_stmt, "si", $target_path, $_SESSION['userId']);
                    
                    if (mysqli_stmt_execute($update_stmt)) {
                        $_SESSION['success'] = 'Profile image updated successfully!';
                        // Refresh user data
                        $user_sql = "SELECT * FROM users WHERE user_id = ?";
                        $user_stmt = mysqli_prepare($conn, $user_sql);
                        mysqli_stmt_bind_param($user_stmt, "i", $_SESSION['userId']);
                        mysqli_stmt_execute($user_stmt);
                        $user_result = mysqli_stmt_get_result($user_stmt);
                        $current_user = mysqli_fetch_assoc($user_result);
                        mysqli_stmt_close($user_stmt);
                    } else {
                        $_SESSION['error'] = 'Failed to update profile image in database.';
                    }
                    mysqli_stmt_close($update_stmt);
                } else {
                    $_SESSION['error'] = 'Failed to upload image.';
                }
            } else {
                $_SESSION['error'] = 'File size too large. Maximum 5MB allowed.';
            }
        } else {
            $_SESSION['error'] = 'Invalid file type. Only JPG, JPEG, and PNG files are allowed.';
        }
    } else {
        $_SESSION['error'] = 'Please select an image file.';
    }
    header("Location: profile.php");
    exit();
}
?>

<div class="container-xl px-4 mt-4">
    <?php include("../includes/alert.php"); ?>
    
    <!-- Display success/error messages for image upload -->
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
    <!-- Account page navigation-->
    <nav class="nav nav-borders">
        <a class="nav-link active ms-0" href="https://www.bootdey.com/snippets/view/bs5-edit-profile-account-details" target="__blank">Profile</a>

    </nav>
    <hr class="mt-0 mb-4">
    <div class="row">
        <div class="col-xl-4">
            <!-- Profile picture card-->
            <div class="card mb-4 mb-xl-0">
                <div class="card-header">Profile Picture</div>
                <div class="card-body text-center">
                    <!-- Profile picture image-->
                    <?php 
                    $profile_image = 'http://bootdey.com/img/Content/avatar/avatar1.png'; // Default image
                    if ($current_user && !empty($current_user['img_path'])) {
                        $profile_image = $current_user['img_path'];
                    }
                    ?>
                    <img class="img-account-profile rounded-circle mb-2" src="<?php echo $profile_image; ?>" alt="Profile Picture" style="width: 150px; height: 150px; object-fit: cover;">
                    
                    <!-- Profile picture upload form-->
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" class="mt-3">
                        <div class="mb-3">
                            <input type="file" class="form-control" name="profile_image" accept="image/jpeg,image/jpg,image/png" required>
                            <div class="small font-italic text-muted mt-2">JPG or PNG no larger than 5 MB</div>
                        </div>
                        <button class="btn btn-primary" type="submit" name="upload_image">Upload new image</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <!-- Account details card-->
            <div class="card mb-4">
                <div class="card-header">Account Details</div>
                <div class="card-body">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                        <h6 class="mb-3">Profile Details</h6>
                        <!-- Form Row-->
                        <div class="row gx-3 mb-3">
                            <!-- Form Group (first name)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputFirstName">First name</label>
                                <input class="form-control" id="inputFirstName" type="text" placeholder="Enter your first name" name="fname" value="<?php echo htmlspecialchars($customer_data['fname'] ?? ''); ?>">
                            </div>
                            <!-- Form Group (last name)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputLastName">Last name</label>
                                <input class="form-control" id="inputLastName" type="text" placeholder="Enter your last name" name="lname" value="<?php echo htmlspecialchars($customer_data['lname'] ?? ''); ?>">
                            </div>
                        </div>
                        <!-- Form Row        -->
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1" for="address">Address</label>
                                <input class="form-control" id="address" type="text" placeholder="Enter your address" name="address" value="<?php echo htmlspecialchars($customer_data['addressline'] ?? ''); ?>">
                            </div>
                            <!-- Form Group (location)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="town">town</label>
                                <input class="form-control" id="town" type="text" placeholder="Enter your town" name="town" value="<?php echo htmlspecialchars($customer_data['town'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="row gx-3 mb-3">
                            <!-- Form Group (phone number)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="zip">zip code</label>
                                <input class="form-control" id="zip" type="tel" placeholder="Enter zipcode" name="zipcode" value="<?php echo htmlspecialchars($customer_data['zipcode'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1" for="title">title</label>
                                <input class="form-control" id="title" type="text" name="title" value="<?php echo htmlspecialchars($customer_data['title'] ?? ''); ?>">
                            </div>
                        </div>
                        <!-- Form Row-->
                        <div class="row gx-3 mb-3">
                            <!-- Form Group (phone number)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputPhone">Phone number</label>
                                <input class="form-control" id="inputPhone" type="tel" placeholder="Enter your phone number" name="phone" value="<?php echo htmlspecialchars($customer_data['phone'] ?? ''); ?>">
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit" name="submit">Save changes</button>
                            <button class="btn btn-danger" type="submit" name="delete_account" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone!')">Delete Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>