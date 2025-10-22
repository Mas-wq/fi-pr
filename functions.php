<?php
function encryptAES($data, $key) {
    $iv = substr(hash('sha256', $key), 0, 16);
    return openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
}

function decryptAES($data, $key) {
    $iv = substr(hash('sha256', $key), 0, 16);
    return openssl_decrypt($data, 'AES-256-CBC', $key, 0, $iv);
}

// RSA
function generateRSAKeys($publicPath, $privatePath) {
    $config = [
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    ];

    $res = openssl_pkey_new($config);
    openssl_pkey_export($res, $privateKey);
    $publicKeyDetails = openssl_pkey_get_details($res);
    $publicKey = $publicKeyDetails["key"];

    file_put_contents($privatePath, $privateKey);
    file_put_contents($publicPath, $publicKey);
}

function encryptRSA($data, $publicKeyPath) {
    $publicKey = file_get_contents($publicKeyPath);
    openssl_public_encrypt($data, $encrypted, $publicKey);
    return base64_encode($encrypted);
}

function decryptRSA($data, $privateKeyPath) {
    $privateKey = file_get_contents($privateKeyPath);
    openssl_private_decrypt(base64_decode($data), $decrypted, $privateKey);
    return $decrypted;
}

function aes_encrypt($data, $key) {
    $iv = substr(hash('sha256', $key), 0, 16);
    return base64_encode(openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv));
}

function aes_decrypt($data, $key) {
    $iv = substr(hash('sha256', $key), 0, 16);
    return openssl_decrypt(base64_decode($data), 'AES-256-CBC', $key, 0, $iv);
}
 
?>
