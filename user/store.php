<?php
session_start();
include("../includes/config.php");
include("../includes/header.php");

// Get form data
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$confirmPass = trim($_POST['confirmPass']);

// Validate required fields
if (empty($name) || empty($email) || empty($password)) {
    $_SESSION['message'] = 'All fields are required';
    header("Location: register.php");
    exit();
}

// Validate password confirmation
if ($password !== $confirmPass) {
    $_SESSION['message'] = 'Passwords do not match';
    header("Location: register.php");
    exit();
}

// Hash password
$password = sha1($password);

// Prepare SQL statement with proper escaping
$sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'customer')";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $password);
    $result = mysqli_stmt_execute($stmt);
    
    if ($result) {
        $_SESSION['userId'] = mysqli_insert_id($conn);
        $_SESSION['email'] = $email;
        $_SESSION['role'] = 'customer';
        $_SESSION['message'] = 'Registration successful!';
        header("Location: profile.php");
        exit();
    } else {
        // Check for duplicate email error
        if (mysqli_errno($conn) == 1062) {
            $_SESSION['message'] = 'Email already exists. Please use a different email.';
        } else {
            $_SESSION['message'] = 'Registration failed. Please try again.';
        }
        header("Location: register.php");
        exit();
    }
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['message'] = 'Database error. Please try again.';
    header("Location: register.php");
    exit();
}
