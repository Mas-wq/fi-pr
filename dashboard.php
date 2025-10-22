<?php
session_start();
if (empty($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<style>
    body {
        margin: 0;
        height: 100vh;
        font-family: "Poppins", sans-serif;
        background: linear-gradient(135deg, #2b1055, #7597de, #ff0844);
        background-size: 200% 200%;
        animation: gradientShift 6s ease infinite;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #f5f5f5;
    }

    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .dashboard {
        background: rgba(25, 25, 25, 0.9);
        padding: 40px 60px;
        border-radius: 20px;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.6);
        width: 80%;
        max-width: 800px;
        text-align: center;
        animation: fadeIn 1s ease;
    }

    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    header h2 {
        color: #ff79c6;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .logout-btn {
        background: linear-gradient(135deg, #ff0844, #ff6a00);
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        transition: 0.3s;
        font-weight: 600;
    }

    .logout-btn:hover {
        background: linear-gradient(135deg, #ff3c78, #ff7f50);
        transform: scale(1.05);
    }

    .cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .card {
        background: linear-gradient(135deg, #3a0ca3, #7209b7);
        color: white;
        text-decoration: none;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.4);
        font-size: 18px;
        font-weight: 500;
        transition: 0.3s;
    }

    .card:hover {
        background: linear-gradient(135deg, #ff006e, #8338ec);
        transform: scale(1.08);
        box-shadow: 0 0 25px rgba(255, 0, 110, 0.5);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
</head>
<body>
<div class="dashboard">
    <header>
        <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h2>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>

    <div class="cards">
        <a href="add_employee.php" class="card">➕ Add Employee</a>
        <a href="view_employees.php" class="card">👁️ View Employees</a>
        <a href="key_management.php" class="card">🔑 Key Management</a>
    </div>
</div>
</body>
</html>
