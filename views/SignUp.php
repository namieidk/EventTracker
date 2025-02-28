<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracker</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(150, 150, 150, 0.8); /* Greyish background with slight transparency */
            backdrop-filter: blur(5px); /* Blur effect */
            -webkit-backdrop-filter: blur(5px); /* For Safari support */
            margin: 0;
        }
        .signup-container {
            max-width: 400px;
            width: 100%;
        }
        .input-group-text {
            background-color: #fff;
            border-right: none;
        }
        .form-control {
            border-left: none;
        }
        .login-text {
            text-align: center;
            margin-top: 15px;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent white card for contrast */
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Sign Up</h2>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="full_name" name="full_name">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    <button type="submit" name="signup" class="btn btn-primary w-100">Sign Up</button>
                    <div class="login-text">
                        <p>Have an account already? <a href="login.php">Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    // Include the database connection file
    include '../database/database.php';

    // Database processing
    if (isset($_POST['signup'])) {
        try {
            // Get form data
            $full_name = $_POST['full_name'] ?? '';
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Prepare and execute the insert statement
            $stmt = $conn->prepare("INSERT INTO user (full_name, username, email, password) VALUES (:full_name, :username, :email, :password)");
            $stmt->bindParam(':full_name', $full_name);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            
            if ($stmt->execute()) {
                // Redirect to login page on success
                header("Location: login.php");
                exit(); // Ensure no further code executes after redirect
            } else {
                echo "<script>alert('Error: Unable to sign up');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
    ?>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>