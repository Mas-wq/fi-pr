<?php
include 'db.php';
include 'functions.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $national_id = $_POST['national_id'] ?? '';
    $salary = $_POST['salary'] ?? '';
    $age = $_POST['age'] ?? '';
    $status = $_POST['status'] ?? 'inactive'; // default لو محددش حاجة

    $aes_key = openssl_random_pseudo_bytes(32);

    $encrypted_nid = aes_encrypt($national_id, $aes_key);
    $encrypted_salary = aes_encrypt($salary, $aes_key);

    $public_key = file_get_contents('keys/public_key.pem');
    $pubKeyRes = openssl_pkey_get_public($public_key);
    openssl_public_encrypt($aes_key, $aes_key_encrypted, $pubKeyRes);

    $aes_key_encoded = base64_encode($aes_key_encrypted);

    // ✅ تأكد إن الجدول فيه الأعمدة age و status
    $stmt = $conn->prepare("INSERT INTO employees (name, national_id, salary, aes_key_encrypted, age, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $encrypted_nid, $encrypted_salary, $aes_key_encoded, $age, $status);

    if ($stmt->execute()) {
        $message = "✅ Employee encrypted & saved successfully!";
    } else {
        $message = "❌ Error: " . $conn->error;
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Employee</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #3b0764, #7e22ce, #9333ea);
        color: #fff;
        margin: 0;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-attachment: fixed;
    }

    .login-container {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(15px);
        padding: 40px;
        border-radius: 20px;
        width: 400px;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        animation: fadeIn 0.8s ease-in-out;
    }

    h2 {
        margin-bottom: 20px;
        color: #f3e8ff;
        letter-spacing: 1px;
    }

    input[type="text"],
    input[type="number"] {
        display: block;
        width: 100%;
        margin: 10px 0;
        padding: 12px;
        border: none;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        font-size: 15px;
        outline: none;
        transition: background 0.3s;
    }

    input:focus {
        background: rgba(255, 255, 255, 0.25);
    }

    .radio-group {
        margin: 15px 0;
        text-align: left;
    }

    .radio-group label {
        display: block;
        margin: 5px 0;
        cursor: pointer;
    }

    .radio-group input[type="radio"] {
        margin-right: 8px;
        transform: scale(1.2);
        accent-color: #ec4899;
    }

    button {
        background: linear-gradient(90deg, #ec4899, #8b5cf6);
        color: #fff;
        border: none;
        padding: 12px 20px;
        border-radius: 10px;
        cursor: pointer;
        width: 100%;
        font-size: 16px;
        font-weight: bold;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    button:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(236, 72, 153, 0.4);
    }

    a.card {
        display: inline-block;
        margin-top: 15px;
        color: #f9a8d4;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
    }

    a.card:hover {
        color: #fff;
        text-shadow: 0 0 10px #f9a8d4;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    p {
        font-weight: 500;
        margin: 10px 0;
    }
</style>
</head>
<body>
<div class="login-container">
    <h2>Add New Employee</h2>
    <?php if ($message): ?>
        <p style="color: <?= str_contains($message, '✅') ? '#4ade80' : '#f87171' ?>;">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="national_id" placeholder="National ID" required>
        <input type="text" name="salary" placeholder="Salary" required>
        <input type="number" name="age" placeholder="Age" required>

        <div class="radio-group">
            <label><input type="radio" name="status" value="active" required> Active ✅</label>
            <label><input type="radio" name="status" value="inactive"> Not Active ❌</label>
        </div>

        <button type="submit">Encrypt & Save</button>
    </form>
    <a href="dashboard.php" class="card">⬅ Back to Dashboard</a>
</div>
</body>
</html>
