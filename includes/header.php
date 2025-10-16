<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$current_dir = dirname($_SERVER['PHP_SELF']);
$path_prefix = '';
if (strpos($current_dir, '/user') !== false || strpos($current_dir, '/item') !== false) {
    $path_prefix = '../';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="includes/style/style.css" rel="stylesheet" type="text/css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <title>shop</title>
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?php echo $path_prefix; ?>index.php">My Shop</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="<?php echo $path_prefix; ?>index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li>
          <li class="nav-item dropdown">
            <?php if (isset($_SESSION['userId'])) {
              echo '<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Dropdown
            </a>';
              echo '<ul class="dropdown-menu">';
              if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                echo "<li><a class='dropdown-item' href='{$path_prefix}item/index.php'>Manage Items</a></li>";
                echo "<li><a class='dropdown-item' href='#'>Orders</a></li>";
                echo "<li><a class='dropdown-item' href='#'>Users</a></li>";
              } else {
                echo '<li><a class="dropdown-item" href="' . $path_prefix . 'user/profile.php">Profile</a></li>';
                echo '<li><a class="dropdown-item" href="' . $path_prefix . 'user/myorders.php">My Orders</a></li>';
              }
              echo "</ul>";
            } ?>
          </li>
        </ul>

        <!-- Search form centered -->
        <form action="<?php echo $path_prefix; ?>index.php" method="GET" class="d-flex mx-auto" style="max-width: 300px;">
          <input class="form-control me-2" type="search" placeholder="Search" name="search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>

        <!-- User info and logout aligned far right -->
        <div class="d-flex align-items-center ms-4">
          <?php
          if (!isset($_SESSION['userId'])) {
            echo "<a href='{$path_prefix}user/login.php' class='btn btn-outline-primary ms-3'>Login</a>";
          } else {
            $user_email = isset($_SESSION['email']) ? $_SESSION['email'] : 'No email found';
            echo "<span class='me-3 text-muted small'>{$user_email}</span>";
            echo "<a href='{$path_prefix}user/logout.php' class='btn btn-outline-danger btn-sm'>Logout</a>";
          }
          ?>
        </div>
      </div>
    </div>
  </nav>
