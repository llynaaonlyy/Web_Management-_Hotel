<?php
$password = $_GET['password'] ?? '';

if ($password == '') {
    echo "Masukkan password di URL. <br>";
    echo "Contoh: ?password=admin1";
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<b>Password asli:</b> " . htmlspecialchars($password) . "<br><br>";
echo "<b>Password hash:</b><br>";
echo $hash;