<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracker</title>
</head>
<body>
    <div>

    
    </div>
</body>
</html><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(150, 150, 150, 0.8); 
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px); 
            margin: 0;
        }
        .login-container {
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
        .signup-text {
            text-align: center;
            margin-top: 15px;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.9); 
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Login</h2>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                    <div class="signup-text">
                        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    include '../database/database.php';

    if (isset($_POST['login'])) {
        try {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $stmt = $conn->prepare("SELECT * FROM user WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                echo "<script>alert('Login successful!');</script>";
                session_start();
                $_SESSION['user_id'] = $user['user_id'];
                  header("Location: Tracker.php");
            } else {
                echo "<script>alert('Invalid username or password');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>