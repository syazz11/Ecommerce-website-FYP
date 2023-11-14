<?php
session_start();

// Redirect to the home page if the user is already logged in
if (isset($_SESSION["user"])) {
    header("Location: home.html");
    exit;
}

$errors = [];

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    require_once "database.php";

    // Retrieve the user's hashed password from the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_stmt_init($conn);
    
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            // Verify the entered password against the stored hashed password
            if (password_verify($password, $user["password"])) {
                // Passwords match, proceed with login
                $_SESSION["user"] = $user;

                // Redirect to home page
                header("Location: home.html");
                exit;
            } else {
                $errors[] = "Password does not match";
            }
        } else {
            $errors[] = "Email not found";
        }
    } else {
        die("SQL statement preparation failed: " . mysqli_error($conn));
    }
}

// If you reach this point, there are login errors
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="container">
    <h1>Login</h1>
    
    <?php
    // Display errors
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
    
    // Display logout button if the user is logged in
    if (isset($_SESSION["user"])) {
        echo '<a href="logout.php" class="btn btn-danger">Logout</a>';
    }
    ?>

    <form action="login.php" method="post">
        <div class="form-group">
            <input type="email" class="form-control" name="email" placeholder="Enter Email">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Enter Password">
        </div>
        <div class="form-btn">
            <input type="submit" class="btn btn-primary" value="Login" name="login">
        </div>
    </form>
</div>
</body>
</html>
