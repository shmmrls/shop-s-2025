<?php
session_start();
include("../includes/header.php");


?>
<div class="container-fluid container-lg">
    <?php include("../includes/alert.php"); ?>
    <form action="store.php" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="mb-3">
            <label for="password2" class="form-label">confirm password</label>
            <input type="password" class="form-control" id="password2" name="confirmPass">
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>

<?php
include("../includes/footer.php");
?>