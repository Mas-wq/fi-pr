<?php
$openssl_conf = "C:\\xampp\\apache\\conf\\openssl.cnf";
if (!file_exists($openssl_conf)) {
    die("OpenSSL config file not found at: $openssl_conf");
}
putenv("OPENSSL_CONF=$openssl_conf");

$msg = "";

if (isset($_POST['generate'])) {
    $rsa_config = [
        "config" => $openssl_conf,
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    ];

    $res = openssl_pkey_new($rsa_config);
    if ($res === false) {
        $msg = "❌ Failed to generate RSA keys. Check configuration.";
    } else {
        openssl_pkey_export($res, $private_key, null, $rsa_config);
        $pub_key_details = openssl_pkey_get_details($res);
        $public_key = $pub_key_details["key"];

        if (!is_dir(__DIR__ . "/keys")) {
            mkdir(__DIR__ . "/keys");
        }

        file_put_contents(__DIR__ . "/keys/private_key.pem", $private_key);
        file_put_contents(__DIR__ . "/keys/public_key.pem", $public_key);

        $msg = "✅ RSA Key Pair generated successfully!";
    }
}

if (isset($_POST['delete'])) {
    $deleted = false;
    if (file_exists(__DIR__ . "/keys/private_key.pem")) {
        unlink(__DIR__ . "/keys/private_key.pem");
        $deleted = true;
    }
    if (file_exists(__DIR__ . "/keys/public_key.pem")) {
        unlink(__DIR__ . "/keys/public_key.pem");
        $deleted = true;
    }
    $msg = $deleted ? "🗑 Keys deleted successfully!" : "⚠️ No keys found to delete.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Key Management</title>
<style>
body {
    margin: 0;
    padding: 0;
    height: 100vh;
    background: linear-gradient(135deg, #7b2ff7, #f107a3);
    font-family: 'Poppins', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
}
.container {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 16px;
    padding: 40px 35px;
    width: 400px;
    box-shadow: 0 8px 30px rgba(0,0,0,.2);
    text-align: center;
    animation: fadeIn 0.8s ease;
}
h2 {
    margin-bottom: 20px;
    color: #6a1b9a;
}
button {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    background: linear-gradient(90deg, #9c27b0, #e91e63);
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    transition: transform 0.2s, box-shadow 0.3s;
}
button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(233, 30, 99, 0.4);
}
.delete-btn {
    background: linear-gradient(90deg, #ff1744, #f50057);
}
.delete-btn:hover {
    box-shadow: 0 5px 15px rgba(255, 23, 68, 0.4);
}
.msg {
    margin-top: 15px;
    font-weight: 600;
}
.success { color: #2e7d32; }
.error { color: #c62828; }
.links a {
    text-decoration: none;
    color: #6a1b9a;
    font-weight: bold;
    transition: color 0.3s;
}
.links a:hover {
    color: #d81b60;
}
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}
</style>
</head>
<body>
<div class="container">
    <h2>🔑 RSA Key Management</h2>
    <form method="post">
        <button type="submit" name="generate">Generate New Key Pair</button>
        <button type="submit" name="delete" class="delete-btn">Delete Current Keys</button>
    </form>

    <?php if (!empty($msg)): ?>
        <div class="msg <?= strpos($msg, '✅') !== false ? 'success' : (strpos($msg, '🗑') !== false ? 'success' : 'error') ?>">
            <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <div class="links" style="margin-top:20px;">
        <?php if (file_exists('keys/private_key.pem')): ?>
            <a href="keys/private_key.pem" download="private_key.pem">⬇️ Download Private Key</a><br><br>
        <?php endif; ?>
        <?php if (file_exists('keys/public_key.pem')): ?>
            <a href="keys/public_key.pem" download="public_key.pem">⬇️ Download Public Key</a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
