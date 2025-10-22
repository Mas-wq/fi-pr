<?php
include 'db.php';
include 'functions.php';

$message = "";
$employees = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['private_key'])) {
    $private_key_content = file_get_contents($_FILES['private_key']['tmp_name']);

    $result = $conn->query("SELECT * FROM employees ORDER BY id DESC");

    while ($row = $result->fetch_assoc()) {
        $aes_key_encrypted = base64_decode($row['aes_key_encrypted']);

        if (openssl_private_decrypt($aes_key_encrypted, $aes_key, $private_key_content)) {
            $nid = aes_decrypt($row['national_id'], $aes_key);
            $salary = aes_decrypt($row['salary'], $aes_key);

            $employees[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'national_id' => $nid,
                'salary' => $salary,
                'created_at' => $row['created_at']
            ];
        } else {
            $employees[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'national_id' => 'Decryption Failed ❌',
                'salary' => 'Decryption Failed ❌',
                'created_at' => $row['created_at']
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Employees (Decrypted)</title>
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #1e1b2e, #3a2c5a, #533b7e);
        color: #fff;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-container {
        background: rgba(25, 25, 35, 0.85);
        padding: 40px;
        border-radius: 16px;
        width: 85%;
        max-width: 900px;
        box-shadow: 0 0 25px rgba(140, 82, 255, 0.4);
        animation: fadeIn 1s ease-in-out;
    }

    h2 {
        text-align: center;
        color: #c9a7ff;
        margin-bottom: 20px;
    }

    form {
        text-align: center;
        margin-bottom: 25px;
    }

    input[type="file"] {
        background: #2f264a;
        border: 1px solid #6b4bff;
        color: #cfcfff;
        padding: 10px;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.3s;
    }

    input[type="file"]:hover {
        background: #42306a;
    }

    button {
        background: linear-gradient(90deg, #6b4bff, #b04bff);
        color: #fff;
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 15px;
        transition: all 0.3s;
        margin-left: 10px;
    }

    button:hover {
        transform: scale(1.05);
        box-shadow: 0 0 15px #9c6bff;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: rgba(50, 40, 75, 0.8);
        border-radius: 10px;
        overflow: hidden;
        animation: fadeUp 0.8s ease;
    }

    th, td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #5e4c8d;
    }

    th {
        background: #6b4bff;
        color: #fff;
        text-transform: uppercase;
        font-weight: 600;
    }

    tr:hover {
        background: rgba(130, 100, 200, 0.2);
        transition: 0.3s;
    }

    a.back {
        display: block;
        margin-top: 20px;
        text-align: center;
        color: #c9a7ff;
        text-decoration: none;
        font-weight: bold;
        transition: 0.3s;
    }

    a.back:hover {
        color: #fff;
        text-shadow: 0 0 8px #b478ff;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
</head>
<body>
<div class="login-container">
    <h2>👁️ View Encrypted Employees</h2>
    <form method="post" enctype="multipart/form-data">
        <p>Upload your <strong>Private Key (.pem)</strong> to decrypt data:</p>
        <input type="file" name="private_key" accept=".pem" required>
        <button type="submit">🔓 Decrypt & View</button>
    </form>

    <?php if (!empty($employees)): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>National ID</th>
            <th>Salary</th>
            <th>Created At</th>
        </tr>
        <?php foreach ($employees as $emp): ?>
        <tr>
            <td><?= htmlspecialchars($emp['id']) ?></td>
            <td><?= htmlspecialchars($emp['name']) ?></td>
            <td><?= htmlspecialchars($emp['national_id']) ?></td>
            <td><?= htmlspecialchars($emp['salary']) ?></td>
            <td><?= htmlspecialchars($emp['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

    <a href="dashboard.php" class="back">⬅ Back to Dashboard</a>
</div>
</body>
</html>
