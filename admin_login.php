<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $admin = $res->fetch_assoc();
        if (password_verify($pass, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login</title>
<style>
    body {
        margin: 0;
        height: 100vh;
        font-family: "Poppins", sans-serif;
        background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
        display: flex;
        justify-content: center;
        align-items: center;
        color: #f5f5f5;
    }

    .login-container {
        background: rgba(20, 20, 20, 0.9);
        padding: 40px 50px;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.6);
        width: 355px;
        text-align: center;
        animation: fadeIn 0.8s ease-in-out;
    }

    h2 {
        margin-bottom: 25px;
        color: #4da6ff;
        font-weight: 600;
        letter-spacing: 1px;
    }

    input {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: none;
        border-radius: 8px;
        background: #2b2b2b;
        color: #fff;
        font-size: 14px;
        outline: none;
        transition: 0.3s;
    }

    input:focus {
        background: #333;
        box-shadow: 0 0 8px #4da6ff;
    }

    button {
        width: 45%;
        padding: 12px;
        background: linear-gradient(135deg, #4da6ff, #0066cc);
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 15px;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 10px;
        font-weight: 600;
    }

    button:hover {
        background: linear-gradient(135deg, #66b2ff, #0080ff);
        transform: scale(1.1);
    }

    .error {
        color: #ff4d4d;
        background: rgba(255, 77, 77, 0.1);
        border: 1px solid #ff4d4d;
        border-radius: 6px;
        padding: 8px;
        margin-bottom: 10px;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
</head>
<body>
<div class="login-container">
    <h2>Admin Login</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
