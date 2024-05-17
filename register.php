<?php
session_start(); // Start the session

if(isset($_POST['add_user'])) {
    try {
        // Database connection
        include_once("includes/config.php");

        // Get form data
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);
        $confirmPassword = htmlspecialchars($_POST['confirm_password']);
        $email = htmlspecialchars($_POST['email']);
        $designation = htmlspecialchars($_POST['designation']);

        // Validate password and password confirmation
        if($password !== $confirmPassword) {
            echo "<script>alert('Passwords do not match.');</script>";
            exit;
        }

        // Check if username or email already exists
        $sqlCheck = "SELECT * FROM users WHERE username = :username OR email = :email";
        $queryCheck = $dbh->prepare($sqlCheck);
        $queryCheck->bindParam(':username', $username, PDO::PARAM_STR);
        $queryCheck->bindParam(':email', $email, PDO::PARAM_STR);
        $queryCheck->execute();
        $existingUser = $queryCheck->fetch(PDO::FETCH_ASSOC);

        // If username or email already exists, display an error message
        if($existingUser) {
            echo "<script>alert('Username or email already exists. Please choose different credentials.');</script>";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert data into database
            $sqlInsert = "INSERT INTO users (username, password_hash, email, designation) VALUES (:username, :password, :email, :designation)";
            $queryInsert = $dbh->prepare($sqlInsert);
            $queryInsert->bindParam(':username', $username, PDO::PARAM_STR);
            $queryInsert->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $queryInsert->bindParam(':email', $email, PDO::PARAM_STR);
            $queryInsert->bindParam(':designation', $designation, PDO::PARAM_STR);
            $queryInsert->execute();

            echo "<script>alert('$designation added successfully.');</script>";
        }
    } catch(PDOException $e) {
        echo "<script>alert('An error occurred: " . $e->getMessage() . "');</script>";
    }
}

// Check if user is logged in
if(isset($_SESSION['userlogin']) && !empty($_SESSION['userlogin'])) {
    header('location:index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add User</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="account-page">
<div class="main-wrapper">
    <div class="account-content">
        <div class="container">
            <div class="account-box">
                <div class="account-wrapper">
                    <h3 class="account-title">Add User</h3>
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" id="password" required>
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                            <div id="password_error" class="text-danger"></div>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Designation</label>
                            <select class="form-control" name="designation" required>
                                <option value="Admin">Admin</option>
                                <option value="User">User</option>
                            </select>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary account-btn" name="add_user">Add User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/jquery-3.2.1.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script>
$(document).ready(function(){
    $('#password, #confirm_password').on('keyup', function () {
        if ($('#password').val() != $('#confirm_password').val()) {
            $('#password_error').html('Passwords do not match').css('color', 'red');
        } else {
            $('#password_error').html('').css('color', 'green');
        }
    });
});
</script>
</body>
</html>
